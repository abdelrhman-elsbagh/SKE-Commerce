@extends('layouts.vertical', ['page_title' => 'Create/Edit Item'])

@section('css')
    @vite([
        'node_modules/jquery-toast-plugin/dist/jquery.toast.min.css'
    ])
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title">{{ isset($item) ? 'Edit Item' : 'Create Item' }}</h4>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <form id="create-edit-item-form" action="{{ isset($item) ? route('items.update', $item->id) : route('items.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @if(isset($item))
                                @method('PUT')
                            @endif
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" class="form-control" id="name" name="name" value="{{ $item->name ?? '' }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" id="description" name="description">{{ $item->description ?? '' }}</textarea>
                            </div>
                            <div class="mb-3">
                                <label for="ar_name" class="form-label">Arabic Name</label>
                                <input type="text" class="form-control" id="ar_name" name="ar_name" value="{{ $item->ar_name ?? '' }}">
                            </div>
                            <div class="mb-3">
                                <label for="ar_description" class="form-label">Arabic Description</label>
                                <textarea class="form-control" id="description" name="ar_description">{{ $item->ar_description ?? '' }}</textarea>
                            </div>
                            <div class="mb-3">
                                <label for="category_id" class="form-label">Category</label>
                                <select class="form-control" id="category_id" name="category_id">
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ (isset($item) && $item->category_id == $category->id) ? 'selected' : '' }}>
                                            {{ $category->name . ($category->ar_name ? ' - ' . $category->ar_name : '') }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="tags" class="form-label">Tags</label>
                                <select class="form-control" id="tags" name="tags[]" multiple>
                                    @foreach($tags as $tag)
                                        <option value="{{ $tag->id }}" {{ (isset($item) && $item->tags->contains($tag->id)) ? 'selected' : '' }}>
                                            {{ $tag->name . ($tag->ar_name ? ' - ' . $tag->ar_name : '') }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="status_type" class="form-label">Status</label>
                                <select class="form-control" id="status_type" name="status">
                                    <option value="active" {{ (isset($item) && $item->status == 'active') ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ (isset($item) && $item->status == 'inactive') ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="title" class="form-label">Title</label>
                                <input type="text" class="form-control" id="title" name="title" value="{{ $item->title ?? '' }}">
                            </div>
                            <div class="mb-3">
                                <label for="title_type" class="form-label">Title Type</label>
                                <select class="form-control" id="title_type" name="title_type">
                                    <option value="default" {{ (isset($item) && $item->title_type == 'default') ? 'selected' : '' }}>Default</option>
                                    <option value="discount" {{ (isset($item) && $item->title_type == 'discount') ? 'selected' : '' }}>Discount</option>
                                    <option value="new" {{ (isset($item) && $item->title_type == 'new') ? 'selected' : '' }}>New</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="image" class="form-label">Image</label>
                                <input type="file" class="form-control" id="image" name="image" accept="image/*">
                                <div class="mt-2" id="image-preview">
                                    @if(isset($item) && $item->getFirstMediaUrl('images'))
                                        <img src="{{ $item->getFirstMediaUrl('images') }}" alt="Image Preview" style="max-width: 200px;">
                                    @endif
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="front_image" class="form-label">Front Image</label>
                                <input type="file" class="form-control" id="front_image" name="front_image" accept="image/*">
                                <div class="mt-2" id="front_image-preview">
                                    @if(isset($item) && $item->getFirstMediaUrl('images'))
                                        <img src="{{ $item->getFirstMediaUrl('images') }}" alt="Image Preview" style="max-width: 200px;">
                                    @endif
                                </div>
                            </div>

                            <div id="sub-items-container">
                                <h5>Sub Items</h5>
                                <button type="button" class="btn btn-secondary mb-3" data-bs-toggle="modal" data-bs-target="#subItemModal">Add Sub Item</button>
                                <table class="table table-bordered">
                                    <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Status</th>
                                        <th>Description</th>
                                        <th>Amount</th>
                                        <th>Price</th>
                                        <th>Max Amount</th>
                                        <th>Min Amount</th>
                                        <th>Country</th>
                                        <th>Image</th>
                                        <th>Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody id="sub-items-table-body">
                                    @if(isset($item))
                                        @foreach($item->subItems as $subItem)
                                            <tr data-id="{{ $subItem->id }}">
                                                <td>
                                                    <input type="hidden" name="sub_items[{{ $loop->index }}][id]" value="{{ $subItem->id }}">
                                                    <input type="hidden" name="sub_items[{{ $loop->index }}][name]" value="{{ $subItem->name }}">
                                                    {{ $subItem->name }}
                                                </td>
                                                <td>
                                                    <input type="hidden" name="sub_items[{{ $loop->index }}][description]" value="{{ $subItem->description }}">
                                                    {{ $subItem->description }}
                                                </td>
                                                <td>
                                                    <input type="hidden" name="sub_items[{{ $loop->index }}][amount]" value="{{ $subItem->amount }}">
                                                    {{ $subItem->amount }}
                                                </td>
                                                <td>
                                                    <input type="hidden" name="sub_items[{{ $loop->index }}][price]" value="{{ $subItem->price }}">
                                                    {{ $subItem->price }}
                                                </td>
                                                <td>
                                                    <input type="text" name="sub_items[{{ $loop->index }}][country]" value="{{ $subItem->country ?? 'Global' }}">
                                                    <span class="sub-item-country-display">{{ $subItem->country ?? 'Global' }}</span> <!-- ✅ Show country -->
                                                </td>
                                                <td>
                                                    @if($subItem->getFirstMediaUrl('images'))
                                                        <img src="{{ $subItem->getFirstMediaUrl('images') }}" alt="Sub Item Image" style="max-width: 100px;">
                                                    @endif
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-danger remove-sub-item" data-id="{{ $subItem->id }}">Remove</button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                    </tbody>
                                </table>
                            </div>

                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sub Item Modal -->
    <div class="modal fade" id="subItemModal" tabindex="-1" aria-labelledby="subItemModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="subItemModalLabel">Add Sub Item</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="create-sub-item-form">
                        <div class="mb-3">
                            <label for="sub_item_name_modal" class="form-label">Sub Item Name</label>
                            <input type="text" class="form-control" id="sub_item_name_modal" name="sub_item_name_modal">
                        </div>
                        <div class="mb-3">
                            <label for="sub_item_description_modal" class="form-label">Sub Item Description</label>
                            <textarea class="form-control" id="sub_item_description_modal" name="sub_item_description_modal"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="sub_item_amount_modal" class="form-label">Sub Item Amount</label>
                            <input type="number" class="form-control" id="sub_item_amount_modal" name="sub_item_amount_modal" required>
                        </div>
                        <div class="mb-3">
                            <label for="sub_item_price_modal" class="form-label">Sub Item Price</label>
                            <input type="number" step="0.5" class="form-control" id="sub_item_price_modal" name="sub_item_price_modal" required>
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="is_custom" name="is_custom">
                            <label class="form-check-label" for="is_custom">Custom</label>
                        </div>
                        <div id="customFields" class="row" style="display: none;">
                            <div class="mb-3 col-sm-12 col-md-6">
                                <label for="minimum_amount" class="form-label">Minimum Amount</label>
                                <input type="number" class="form-control" id="minimum_amount" name="minimum_amount">
                            </div>
                            <div class="mb-3 col-sm-12 col-md-6">
                                <label for="max_amount" class="form-label">Maximum Amount</label>
                                <input type="number" class="form-control" id="max_amount" name="max_amount">
                            </div>
                        </div>
                        <div class="mb-3 col-sm-12 col-md-12">
                            <label for="sub_status" class="form-label">Status</label>
                            <select class="form-control" id="sub_status" name="sub_status">
                                <option value="active" selected>Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="sub_item_country_modal" class="form-label">Country</label>
                            <select class="form-control" id="sub_item_country_modal" name="sub_item_country_modal">
                                <option value="Global">Global</option>
                                @php
                                        $countries = json_decode(file_get_contents(public_path('assets/countries.json')), true);
                                @endphp
                            @foreach($countries as $country)
                                    <option value="{{ $country['name'] }}">{{ $country['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="sub_item_image_modal" class="form-label">Sub Item Image</label>
                            <input type="file" class="form-control" id="sub_item_image_modal" name="sub_item_image_modal" accept="image/*">
                            <div class="mt-2" id="sub-item-image-preview-modal"></div>
                        </div>
                        <button type="submit" class="btn btn-primary">Add Sub Item</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    @vite([
        'node_modules/jquery-toast-plugin/dist/jquery.toast.min.js'
    ])

    <script>
        $(document).ready(function() {

            $('#is_custom').change(function() {
                if ($(this).is(':checked')) {
                    $('#customFields').show();
                } else {
                    $('#customFields').hide();
                }
            });

            $('#image').change(function() {
                let reader = new FileReader();
                reader.onload = function(e) {
                    $('#image-preview').html('<img src="' + e.target.result + '" alt="Image Preview" style="max-width: 200px;">');
                }
                reader.readAsDataURL(this.files[0]);
            });

            $('#front_image').change(function() {
                let reader = new FileReader();
                reader.onload = function(e) {
                    $('#front_image-preview').html('<img src="' + e.target.result + '" alt="Image Preview" style="max-width: 200px;">');
                }
                reader.readAsDataURL(this.files[0]);
            });

            $('#sub_item_image_modal').change(function() {
                let reader = new FileReader();
                reader.onload = function(e) {
                    $('#sub-item-image-preview-modal').html('<img src="' + e.target.result + '" alt="Sub Item Image Preview" style="max-width: 200px;">');
                }
                reader.readAsDataURL(this.files[0]);
            });

            $('#create-sub-item-form').on('submit', function(e) {
                e.preventDefault();

                let subItemName = $('#sub_item_name_modal').val();
                let subItemDescription = $('#sub_item_description_modal').val();
                let subItemAmount = $('#sub_item_amount_modal').val();
                let max_amount = $('#max_amount').val();
                let sub_status = $('#sub_status').val();
                let minimum_amount = $('#minimum_amount').val();
                let is_custom = $('#is_custom').is(':checked') ? 1 : 0;
                let subItemPrice = $('#sub_item_price_modal').val();
                let subItemImage = $('#sub_item_image_modal')[0].files[0];
                let subItemCountry = $('#sub_item_country_modal').val();

                let subItemCount = $('#sub-items-table-body tr').length;

                let subItemRow = `
                    <tr data-temp-id="${subItemCount}">
                        <td>
                            <input type="hidden" name="sub_items[${subItemCount}][name]" value="${subItemName}">
                            ${subItemName}
                        </td>
                        <td>
                            <input type="hidden" name="sub_items[${subItemCount}][sub_status]" value="${sub_status}">
                            ${sub_status}
                        </td>
                        <td>
                            <input type="hidden" name="sub_items[${subItemCount}][description]" value="${subItemDescription}">
                            ${subItemDescription}
                        </td>
                        <td>
                            <input type="hidden" name="sub_items[${subItemCount}][amount]" value="${subItemAmount}">
                            ${subItemAmount}
                        </td>
                        <td>
                            <input type="hidden" name="sub_items[${subItemCount}][price]" value="${subItemPrice}">
                            ${subItemPrice}
                        </td>
                        <td>
                            <input type="hidden" name="sub_items[${subItemCount}][minimum_amount]" value="${minimum_amount}">
                            ${minimum_amount}
                        </td>
                        <td>
                            <input type="hidden" name="sub_items[${subItemCount}][max_amount]" value="${max_amount}">
                            <input type="hidden" name="sub_items[${subItemCount}][is_custom]" value="${is_custom}">
                            ${max_amount}
                        </td>
                         <td>
                            <input type="hidden" name="sub_items[${subItemCount}][country]" value="${subItemCountry}">
                            ${subItemCountry}
                        </td>
                        <td>
                            <input type="file" name="sub_items[${subItemCount}][image]" class="sub-item-image-file" data-index="${subItemCount}" style="display: none;">
                            <img src="" alt="Sub Item Image" class="sub-item-image-preview" style="max-width: 100px;">
                        </td>
                        <td>
                            <button type="button" class="btn btn-danger remove-sub-item" data-id="${subItemCount}">Remove</button>
                        </td>
                    </tr>
                `;

                $('#sub-items-table-body').append(subItemRow);

                if (subItemImage) {
                    let reader = new FileReader();
                    reader.onload = function(e) {
                        $('#sub-items-table-body').find('tr:last .sub-item-image-preview').attr('src', e.target.result);
                    }
                    reader.readAsDataURL(subItemImage);

                    let imageData = new DataTransfer();
                    imageData.items.add(subItemImage);
                    $('#sub-items-table-body').find('tr:last .sub-item-image-file').prop('files', imageData.files);
                }

                // Clear modal form fields
                $('#create-sub-item-form')[0].reset();
                $('#sub-item-image-preview-modal').html('');
                $('#subItemModal').modal('hide');
            });

            $('#create-edit-item-form').on('submit', function(e) {
                e.preventDefault();

                var formData = new FormData(this);

                // Append sub-item images manually
                $('#sub-items-table-body .sub-item-image-file').each(function() {
                    let index = $(this).data('index');
                    let files = $(this).prop('files');
                    if (files && files.length > 0) {
                        formData.append(`sub_items[${index}][image]`, files[0]);
                    }
                });

                $.ajax({
                    url: $(this).attr('action'),
                    method: $(this).attr('method'),
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        $.toast().reset('all'); // Reset previous toasts
                        $.toast({
                            heading: 'Success',
                            text: 'Item created successfully.',
                            icon: 'success',
                            loader: true,
                            loaderBg: '#f96868',
                            position: 'top-right',
                            hideAfter: 3000,
                            afterHidden: function () {
                                window.location.href = "{{ route('items.index') }}";
                            }
                        });

                        // Optionally, reset the form fields
                        $('#create-edit-item-form')[0].reset();
                        // $('#image-preview').html('');
                        // $('#sub-items-table-body').html('');
                    },
                    error: function(response) {
                        $.toast().reset('all'); // Reset previous toasts
                        $.toast({
                            heading: 'Error',
                            text: 'There was an error creating/updating the item.',
                            icon: 'error',
                            loader: true,
                            loaderBg: '#f96868',
                            position: 'top-right',
                            hideAfter: 3000
                        });
                    }
                });
            });

            $(document).on('click', '.remove-sub-item', function() {
                let subItemId = $(this).data('id');
                $(this).closest('tr').remove();

                // Append a hidden input to indicate removal of a sub-item
                $('<input>').attr({
                    type: 'hidden',
                    name: 'sub_items_to_remove[]',
                    value: subItemId
                }).appendTo('#create-edit-item-form');
            });
        });
    </script>
@endsection
