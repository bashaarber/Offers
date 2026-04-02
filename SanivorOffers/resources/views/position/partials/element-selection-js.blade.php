<script>
    function initializePositionElementSelection(organigramToggles, groupElementToggles, options = {}) {
        const setChevronState = (icon, isOpen) => {
            if (!icon) return;
            icon.classList.toggle('fa-chevron-right', !isOpen);
            icon.classList.toggle('fa-chevron-down', isOpen);
        };

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
                setChevronState(icon, !isOpen);
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
                setChevronState(icon, !isOpen);
            });
        });

        if (options.expandSelected) {
            document.querySelectorAll('.element-checkbox:checked').forEach(checkbox => {
                const elementContainer = checkbox.closest('.elements');
                if (!elementContainer) return;

                elementContainer.style.display = 'block';
                const groupToggle = elementContainer.previousElementSibling;
                setChevronState(groupToggle ? groupToggle.querySelector('i') : null, true);

                const groupContainer = elementContainer.closest('.group-elements');
                if (!groupContainer) return;

                groupContainer.style.display = 'block';
                const organigramToggle = groupContainer.previousElementSibling;
                setChevronState(organigramToggle ? organigramToggle.querySelector('i') : null, true);
            });
        }
    }
</script>
