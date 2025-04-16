document.addEventListener("DOMContentLoaded", () => {
    loadTasks();

    const form = document.getElementById("task-form");
    form.addEventListener("submit", async (e) => {
        e.preventDefault();

        const title = document.getElementById("title").value;
        const deadline = document.getElementById("deadline").value;
        const priority = document.getElementById("priority").value;

        if (!title || !deadline || !priority) {
            alert("All fields are required!");
            return;
        }

        const formData = new FormData(form);

        const response = await fetch("../ajax/add_task.php", {
            method: "POST",
            body: formData
        });

        const result = await response.text();
        alert(result);
        form.reset();
        loadTasks();
    });

    // Attach filter event listeners (if any dropdowns exist)
    const statusFilter = document.getElementById("filter-status");
    const priorityFilter = document.getElementById("filter-priority");

    if (statusFilter && priorityFilter) {
        statusFilter.addEventListener("change", filterTasks);
        priorityFilter.addEventListener("change", filterTasks);
    }
});

async function loadTasks() {
    const status = document.getElementById("filter-status")?.value || '';
    const priority = document.getElementById("filter-priority")?.value || '';

    const res = await fetch(`../ajax/load_tasks.php?status=${status}&priority=${priority}`);
    const html = await res.text();
    document.getElementById("task-list").innerHTML = html;
}

async function filterTasks() {
    await loadTasks(); // just reuse loadTasks to keep it clean
}

async function deleteTask(id) {
    const res = await fetch(`../ajax/delete_task.php?id=${id}`);
    const result = await res.text();
    alert(result);
    loadTasks();
}
