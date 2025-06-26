const selectedCategories = new Set();

function toggleCategory(el) {
    const categoryId = el.dataset.id;

    if (selectedCategories.has(categoryId)) {
        selectedCategories.delete(categoryId);
        el.classList.remove('selected');
    } else {
        selectedCategories.add(categoryId);
        el.classList.add('selected');
    }

    renderHiddenInputs();
}

function renderHiddenInputs() {
    const container = document.getElementById('selected-categories');
    container.innerHTML = '';

    selectedCategories.forEach(id => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'categories[]';
        input.value = id;
        container.appendChild(input);
    });
}

document.addEventListener('DOMContentLoaded', () => {
    // read the initial value embedded on the sever side
    if (window.initialCategoryIds && Array.isArray(window.initialCategoryIds)) {
        window.initialCategoryIds.forEach(id => selectedCategories.add(String(id)));
    }

    // re-add the selected class to the selected tag
    document.querySelectorAll('.category-tag').forEach(tag => {
        if (selectedCategories.has(tag.dataset.id)) {
            tag.classList.add('selected');
        }

        tag.addEventListener('click', function () {
            toggleCategory(this);
        });
    });

    renderHiddenInputs();
})