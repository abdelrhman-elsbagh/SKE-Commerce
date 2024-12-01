@extends('layouts.vertical', ['page_title' => 'API Items'])

@section('css')
    @vite(['node_modules/jquery-toast-plugin/dist/jquery.toast.min.css'])
    <style>
        /* Styling the Parent Item Row */
        .parent-item {
            background-color: #f8f9fa;
            font-weight: bold;
        }

        /* Styling the Sub-item Rows */
        .sub-item {
            background-color: #e9ecef;
        }

        /* Icon for sub-items */
        .sub-item-icon {
            margin-right: 10px;
            color: #007bff;
            font-size: 16px;
        }

        /* Styling the collapse button */
        .collapse-icon {
            cursor: pointer;
            font-size: 16px;
            margin-right: 10px;
        }

        /* Styling the table headers (th) */
        .table th {
            background-color: #ffffff; /* White background for table header */
            color: #333; /* Dark text color for readability */
        }

        /* Making the checkboxes smaller */
        .select-checkbox {
            width: 20px;
            height: 20px;
        }
    </style>
@endsection

@section('content')
    <div class="container">
        <h1 class="mt-5">Fetch and Import Items</h1>
        <div class="form-group mt-2">
            <label for="domain">Domain:</label>
            <select class="form-control" id="domain" required>
                @foreach($users as $user)
                    <option value="{{ $user->domain }}" data-source-key="{{ $user->secret_key }}" data-client-id="{{ $user->id }}">{{ $user->name }} ({{ $user->domain }})</option>
                @endforeach
            </select>
        </div>

        <button id="fetchItems" class="btn btn-primary mt-3">Fetch Items</button>

        <button id="importItems" class="btn btn-success mt-3">Import Selected</button>

        <div class="mt-4" id="itemsContainer" style="display:none;">
            <h2>Items to Import</h2>
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>Item / Sub-item Name</th>
                    <th>Category</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Amount</th>
                    <th>Parent Item</th>
                    <th class="text-center">Select</th>
                </tr>
                </thead>
                <tbody id="itemsTableBody">
                <!-- Parent items and their sub-items will be loaded dynamically -->
                </tbody>
            </table>
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
            const itemsTableBody = document.getElementById('itemsTableBody');
            const domainInput = document.getElementById('domain');

            function getSourceKey() {
                const selectedOption = domainInput.options[domainInput.selectedIndex];
                return selectedOption.getAttribute('data-source-key');
            }

            function getClientId() {
                const selectedOption = domainInput.options[domainInput.selectedIndex];
                return selectedOption.getAttribute('data-client-id');
            }

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
                const sourceKey = getSourceKey();
                const clientId = getClientId();
                axios.post(domainInput.value + '/api/fetch-items', {
                    source_key: sourceKey,
                    client_id: clientId,
                    domain: window.location.origin ?? ""
                }, {
                    headers: {
                        'Content-Type': 'application/json'
                    }
                })
                    .then(response => {
                        const items = response.data.items;
                        let rows = '';

                        items.forEach(item => {
                            const parentId = `parent-${item.id}`;
                            const subItemClass = `sub-item-${item.id}`;

                            // Parent item row
                            rows += `
                            <tr class="parent-item" id="${parentId}">
                                <td>
                                    <span class="collapse-icon" data-target="${subItemClass}">➤</span>
                                    ${item.name || 'N/A'}
                                </td>
                                <td>${item.category.name || 'N/A'}</td>
                                <td>${item.description || 'N/A'}</td>
                                <td>${item.price || 'N/A'}</td>
                                <td>${item.amount || 'N/A'}</td>
                                <td></td>
                                <td></td>
                            </tr>`;

                            // Sub-items rows
                            if (item.sub_items && item.sub_items.length > 0) {
                                item.sub_items.forEach(sub => {
                                    rows += `
                                    <tr class="sub-item ${subItemClass}" style="display: none;">
                                        <td>
                                            <i class="sub-item-icon fas fa-cogs"></i> ${sub.name || 'N/A'}
                                        </td>
                                        <td>${item.category.name || 'N/A'}</td>
                                        <td>${sub.description || 'N/A'}</td>
                                        <td>${sub.price || 'N/A'}</td>
                                        <td>${sub.amount || 'N/A'}</td>
                                        <td>${item.name || 'N/A'}</td>
                                        <td class="text-center">
                                            <input type="checkbox" name="sub_items" value="${sub.id}" data-sub-item-id="${sub.id}" data-item-id="${item.id}" data-item-name="${item.name}" data-item-description="${item.description}" data-sub-user-id="${sub.user_id}" class="select-checkbox">
                                        </td>
                                    </tr>`;
                                });
                            }
                        });

                        itemsTableBody.innerHTML = rows;
                        itemsContainer.style.display = 'block';

                        // Add toggle functionality to collapse icons
                        const collapseIcons = document.querySelectorAll('.collapse-icon');
                        collapseIcons.forEach(icon => {
                            icon.addEventListener('click', function () {
                                const targetClass = this.getAttribute('data-target');
                                const rowsToToggle = document.querySelectorAll(`.${targetClass}`);
                                rowsToToggle.forEach(row => {
                                    row.style.display = row.style.display === 'none' ? '' : 'none';
                                });

                                // Change collapse icon direction
                                this.textContent = this.textContent === '➤' ? '▼' : '➤';
                            });
                        });

                        showToast('Items fetched successfully.', 'success');
                    })
                    .catch(error => {
                        console.error('Error fetching items:', error);
                        showToast('Failed to fetch items. ' + (error.response ? error.response.data.message : 'Network error'), 'error');
                    });
            });

            importItemsBtn.addEventListener('click', function () {
                const clientId = getClientId();
                const selectedSubItems = document.querySelectorAll('input[name="sub_items"]:checked');
                const subItemsToImport = Array.from(selectedSubItems).map(subItem => {
                    const subItemRow = subItem.closest('tr');
                    return {
                        external_id: subItem.getAttribute('data-sub-item-id').trim(),
                        item_id: subItem.getAttribute('data-item-id'),
                        user_id: subItem.getAttribute('data-sub-user-id'),
                        item_name: subItem.getAttribute('data-item-name') || "",
                        item_description: subItem.getAttribute('data-item-description') || "",
                        name: subItemRow.cells[0]?.innerText || "",
                        price: subItemRow.cells[1]?.innerText?.split(':')[1]?.trim() || 0,
                        amount: subItemRow.cells[2]?.innerText?.split(':')[1]?.trim() || 0,
                        description: subItemRow.cells[3]?.innerText?.split(':')[1]?.trim() || "",
                    };
                });

                axios.post(`/admin/items/import`, {
                    sub_items: subItemsToImport,
                    domain: domainInput.value,
                    client_id: clientId,
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
