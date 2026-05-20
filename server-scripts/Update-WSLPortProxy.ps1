# Update-WSLPortProxy.ps1
# Run at Windows startup via a scheduled task. Fixes the WSL-IP-changes-on-reboot
# problem by rebuilding portproxy rules for both the prod (8000) and QA (9000) apps.
#
# Install (one-time, elevated PowerShell):
#   schtasks /Create /TN "WSL Port Proxy" /SC ONSTART /RL HIGHEST /RU SYSTEM `
#     /TR "powershell.exe -ExecutionPolicy Bypass -File C:\app\Update-WSLPortProxy.ps1"
#
# Manual run (elevated): powershell -ExecutionPolicy Bypass -File C:\app\Update-WSLPortProxy.ps1

$ErrorActionPreference = "Stop"
$logFile = "C:\app\wsl-portproxy.log"

function Log($msg) {
    $line = "$(Get-Date -Format 'yyyy-MM-dd HH:mm:ss')  $msg"
    Write-Host $line
    Add-Content -Path $logFile -Value $line
}

Log "=== Boot: updating WSL portproxy rules ==="

# 1. Make sure WSL/Docker is up. `wsl hostname -I` triggers WSL to start if needed.
$wslIp = $null
for ($i = 1; $i -le 30; $i++) {
    try {
        $raw = (wsl hostname -I) 2>$null
        if ($raw) {
            $wslIp = $raw.Trim().Split(' ')[0]
            if ($wslIp -match '^\d+\.\d+\.\d+\.\d+$') { break }
        }
    } catch {}
    Start-Sleep -Seconds 2
}

if (-not $wslIp) {
    Log "ERROR: could not get WSL IP after 60s — aborting."
    exit 1
}
Log "WSL IP: $wslIp"

# 2. Rebuild portproxy rules for both apps.
$ports = @(8000, 9000)
foreach ($p in $ports) {
    netsh interface portproxy delete v4tov4 listenport=$p listenaddress=0.0.0.0 2>$null | Out-Null
    netsh interface portproxy add    v4tov4 listenport=$p listenaddress=0.0.0.0 connectport=$p connectaddress=$wslIp | Out-Null
    Log "portproxy 0.0.0.0:$p -> ${wslIp}:$p"
}

# 3. Make sure docker is running inside WSL so the apps come up.
try {
    wsl -d Ubuntu -u root -- service docker start | Out-Null
    Log "docker service started in WSL"
} catch {
    Log "WARN: could not start docker service in WSL: $_"
}

Log "=== Done ==="
