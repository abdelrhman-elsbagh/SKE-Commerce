@extends('layouts.vertical', ['page_title' => 'API Items'])

@section('css')
    @vite(['node_modules/jquery-toast-plugin/dist/jquery.toast.min.css'])
@endsection

@section('content')
    <div class="container">
        <h1 class="mt-5">Fetch and Import Items</h1>
        <div class="form-group">
            <label for="destination_key">Secret Key:</label>
            <input type="text" class="form-control" id="destination_key" required value="35ee02c5-884c-4274-a6ac-0db39f2dbfee">
            @if(auth()->check())
                <input type="hidden" class="form-control" id="source_key" value="{{ auth()->user()->secret_key }}">
            @endif
        </div>
        <div class="form-group mt-2">
            <label for="domain">Domain:</label>
            <input type="url" class="form-control" id="domain" required value="http://localhost:8003">
        </div>
        <button id="fetchItems" class="btn btn-primary mt-3">Fetch Items</button>

        <div class="mt-4" id="itemsContainer" style="display:none;">
            <h2>Items to Import</h2>
            <table class="table" id="itemsTable">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Description</th>
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
    <!-- Include Axios -->
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const fetchItemsBtn = document.getElementById('fetchItems');
            const importItemsBtn = document.getElementById('importItems');
            const itemsContainer = document.getElementById('itemsContainer');
            const itemsTableBody = document.getElementById('itemsTable').querySelector('tbody');
            const destinationKeyInput = document.getElementById('destination_key');
            const sourceKeyInput = document.getElementById('source_key');
            const domainInput = document.getElementById('domain');

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

            fetchItemsBtn.addEventListener('click', function () {
                axios.post(domainInput.value + '/api/fetch-items', {
                    destination_key: destinationKeyInput.value,
                    source_key: sourceKeyInput.value,
                    domain: domainInput.value
                }, {
                    headers: {
                        'Content-Type': 'application/json'
                    }
                })
                    .then(response => {
                        const items = response.data.items;
                        console.log(response.data.items)
                        let rows = '';
                        items.forEach((item, index) => {
                            rows += `
                            <tr class="table-info">
                                <td>${item.name}</td>
                                <td>${item.description}</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>

                            </tr>
                        `;
                            if (item.sub_items && item.sub_items.length > 0) {
                                item.sub_items.forEach(sub => {
                                    rows += `
                                    <tr class="table-secondary">
                                        <td colspan="2">
                                            ${sub.name}
                                        </td>
                                        <td colspan="1">
                                            <div>Price: ${sub.price || 'N/A'}</div>
                                        </td>
                                        <td colspan="1">
                                            <div>Amount: ${sub.amount || 'N/A'}</div>
                                        </td>
                                        <td colspan="2">
                                            <div>Description: ${sub.description || 'N/A'}</div>
                                        </td>
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
                        item_external_id: subItem.getAttribute('data-item-id'),
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
                        itemsTableBody.innerHTML = ''; // Clear the table to prevent duplicate imports on subsequent fetches
                    })
                    .catch(error => {
                        console.error('Error importing sub-items:', error);
                        showToast('Failed to import sub-items. ' + (error.response ? error.response.data.message : 'Network error'), 'error');
                    });
            });

        });
    </script>
@endsection
