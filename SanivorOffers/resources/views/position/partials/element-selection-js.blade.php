<script>
    function initializePositionElementSelection(organigramToggles, groupElementToggles) {
        organigramToggles.forEach(toggle => {
            const groupContainer = toggle.nextElementSibling;
            const icon = toggle.querySelector('i');
            if (groupContainer) {
                groupContainer.style.display = 'none';
            }
            toggle.addEventListener('click', function() {
                if (!groupContainer) return;
                const isOpen = groupContainer.style.display === 'block';
                groupContainer.style.display = isOpen ? 'none' : 'block';
                if (icon) {
                    icon.classList.toggle('fa-chevron-right', isOpen);
                    icon.classList.toggle('fa-chevron-down', !isOpen);
                }
            });
        });

        groupElementToggles.forEach(toggle => {
            const elementContainer = toggle.nextElementSibling;
            const icon = toggle.querySelector('i');
            if (elementContainer) {
                elementContainer.style.display = 'none';
            }
            toggle.addEventListener('click', function() {
                if (!elementContainer) return;
                const isOpen = elementContainer.style.display === 'block';
                elementContainer.style.display = isOpen ? 'none' : 'block';
                if (icon) {
                    icon.classList.toggle('fa-chevron-right', isOpen);
                    icon.classList.toggle('fa-chevron-down', !isOpen);
                }
            });
        });
    }
</script>
