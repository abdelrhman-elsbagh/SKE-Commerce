@extends('layouts.vertical', ['page_title' => 'API Items'])

@section('css')
    @vite(['node_modules/jquery-toast-plugin/dist/jquery.toast.min.css'])
@endsection

@section('content')
    <div class="container">
        <h1 class="mt-5">Fetch and Import Items</h1>
        <div class="form-group mt-2">
            <label for="domain">Domain:</label>
            <select class="form-control" id="domain" required>
                @foreach($users as $user)
                    <option value="{{ $user->domain }}" data-source-key="{{ $user->secret_key }}">{{ $user->name }} ({{ $user->domain }})</option>
                @endforeach
            </select>
        </div>

        <button id="fetchItems" class="btn btn-primary mt-3">Fetch Items</button>

        <div class="mt-4" id="itemsContainer" style="display:none;">
            <h2>Items to Import</h2>
            <table class="table" id="itemsTable">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Description</th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <!-- Items and sub-items will be loaded here by JavaScript -->
                </tbody>
            </table>
            <button id="importItems" class="btn btn-success mt-3">Import Selected</button>
        </div>
    </div>
@endsection

@section('script')
    @vite(['node_modules/jquery-toast-plugin/dist/jquery.toast.min.js'])
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const fetchItemsBtn = document.getElementById('fetchItems');
            const importItemsBtn = document.getElementById('importItems');
            const itemsContainer = document.getElementById('itemsContainer');
            const itemsTableBody = document.getElementById('itemsTable').querySelector('tbody');
            const domainInput = document.getElementById('domain');

            // Function to get the source_key dynamically
            function getSourceKey() {
                const selectedOption = domainInput.options[domainInput.selectedIndex];
                return selectedOption.getAttribute('data-source-key');
            }

            // Toast notification function
            function showToast(message, type = 'error') {
                $.toast({
                    heading: type === 'error' ? 'Error' : 'Success',
                    text: message,
                    icon: type,
                    loader: true,
                    loaderBg: '#f96868',
                    position: 'top-right',
                    hideAfter: 3000
                });
            }

            // Fetch items event
            fetchItemsBtn.addEventListener('click', function () {
                const sourceKey = getSourceKey(); // Get source_key dynamically
                axios.post(domainInput.value + '/api/fetch-items', {
                    source_key: sourceKey,
                    domain: domainInput.value
                }, {
                    headers: {
                        'Content-Type': 'application/json'
                    }
                })
                    .then(response => {
                        const items = response.data.items;
                        let rows = '';
                        items.forEach(item => {
                            rows += `
                                <tr class="table-info">
                                    <td>${item.name}</td>
                                    <td>${item.description}</td>
                                </tr>
                            `;
                            if (item.sub_items && item.sub_items.length > 0) {
                                item.sub_items.forEach(sub => {
                                    rows += `
                                        <tr class="table-secondary">
                                            <td colspan="2">${sub.name}</td>
                                            <td>Price: ${sub.price || 'N/A'}</td>
                                            <td>Amount: ${sub.amount || 'N/A'}</td>
                                            <td>Description: ${sub.description || 'N/A'}</td>
                                            <td>
                                                <input type="checkbox" name="sub_items" value="${sub.id}" data-sub-item-id="${sub.id}"
                                                    data-sub-user-id="${sub.user_id}"
                                                    data-item-name="${item.name}" data-item-description="${item.description}" data-item-id="${item.id}">
                                            </td>
                                        </tr>
                                    `;
                                });
                            }
                        });
                        itemsTableBody.innerHTML = rows;
                        itemsContainer.style.display = 'block';
                        showToast('Items fetched successfully.', 'success');
                    })
                    .catch(error => {
                        console.error('Error fetching items:', error);
                        showToast('Failed to fetch items. ' + (error.response ? error.response.data.message : 'Network error'), 'error');
                    });
            });

            // Import items event
            importItemsBtn.addEventListener('click', function () {
                const selectedSubItems = document.querySelectorAll('input[name="sub_items"]:checked');
                const subItemsToImport = Array.from(selectedSubItems).map(subItem => {
                    const subItemRow = subItem.closest('tr');
                    return {
                        external_id: subItem.getAttribute('data-sub-item-id'),
                        item_id: subItem.getAttribute('data-item-id'),
                        user_id: subItem.getAttribute('data-sub-user-id'),
                        item_name: subItem.getAttribute('data-item-name'),
                        item_description: subItem.getAttribute('data-item-description'),
                        name: subItemRow.cells[0].innerText,
                        price: subItemRow.cells[1].innerText.split(':')[1].trim(),
                        amount: subItemRow.cells[2].innerText.split(':')[1].trim(),
                        description: subItemRow.cells[3].innerText.split(':')[1].trim(),
                    };
                });

                axios.post(`/admin/items/import`, {
                    sub_items: subItemsToImport,
                    domain: domainInput.value
                }, {
                    headers: {
                        'Content-Type': 'application/json'
                    }
                })
                    .then(response => {
                        showToast('Sub-items imported successfully.', 'success');
                        itemsContainer.style.display = 'none'; // Optionally hide the container after import
                        itemsTableBody.innerHTML = ''; // Clear the table
                    })
                    .catch(error => {
                        console.error('Error importing sub-items:', error);
                        showToast('Failed to import sub-items. ' + (error.response ? error.response.data.message : 'Network error'), 'error');
                    });
            });
        });
    </script>
@endsection
