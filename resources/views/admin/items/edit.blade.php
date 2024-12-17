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
                                <textarea class="form-control" id="ar_description" name="ar_description">{{ $item->ar_description ?? '' }}</textarea>
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
                                    <option value="active" {{ $item->status == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ $item->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="title" class="form-label">Title</label>
                                <input type="text" class="form-control" id="title" name="title" value="{{ $item->title ?? '' }}">
                            </div>
                            <div class="mb-3">
                                <label for="title_type" class="form-label">Title Type</label>
                                <select class="form-control" id="title_type" name="title_type">
                                    <option value="default" {{ $item->title_type == 'default' ? 'selected' : '' }}>Default</option>
                                    <option value="discount" {{ $item->title_type == 'discount' ? 'selected' : '' }}>Discount</option>
                                    <option value="new" {{ $item->title_type == 'new' ? 'selected' : '' }}>New</option>
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
                                    @if(isset($item) && $item->getFirstMediaUrl('front_image'))
                                        <img src="{{ $item->getFirstMediaUrl('front_image') }}" alt="Image Preview" style="max-width: 200px;">
                                    @endif
                                </div>
                            </div>

                            <div id="sub-items-container">
                                <h5>Sub Items</h5>
                                <button type="button" class="btn btn-secondary mb-3" data-bs-toggle="modal" data-bs-target="#subItemModal">Add / Edit Sub Item</button>
                                <table class="table table-bordered">
                                    <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Description</th>
                                        <th>Amount</th>
                                        <th>Price</th>
                                        <th>Min Amount</th>
                                        <th>Max Amount</th>
                                        <th>Dynamic</th>
                                        <th>Status</th>
                                        <th class="text-center">Image</th>
                                        <th>Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody id="sub-items-table-body">
                                    @if(isset($item) && $item->subItems->isNotEmpty())
                                        @foreach($item->subItems as $subItem)
                                            <tr data-id="{{ $subItem->id }}">
                                                <td>
                                                    <input type="hidden" name="sub_items[{{ $loop->index }}][id]" value="{{ $subItem->id }}">
                                                    <input type="hidden" name="sub_items[{{ $loop->index }}][name]" value="{{ $subItem->name }}">
                                                    <span>{{ $subItem->name }}</span>
                                                </td>
                                                <td>
                                                    <input type="hidden" name="sub_items[{{ $loop->index }}][description]" value="{{ $subItem->description }}">
                                                    <span>{{ $subItem->description }}</span>
                                                </td>
                                                <td>
                                                    <input type="hidden" name="sub_items[{{ $loop->index }}][amount]"
                                                           value="{{ $subItem->amount }}">
                                                    <span>{{ $subItem->amount }}</span>
                                                </td>
                                                <td>
                                                    <input type="hidden" name="sub_items[{{ $loop->index }}][price]"
                                                           value="{{ $subItem->price }}">
                                                    <span>{{ $subItem->price }}</span>
                                                </td>

                                                <td>
                                                    <input type="hidden" name="sub_items[{{ $loop->index }}][minimum_amount]"
                                                           value="{{ $subItem->minimum_amount }}">
                                                    <span>{{ $subItem->minimum_amount }}</span>
                                                </td>
                                                <td>
                                                    <input type="hidden" name="sub_items[{{ $loop->index }}][max_amount]"
                                                           value="{{ $subItem->max_amount }}">
                                                    <span>{{ $subItem->max_amount }}</span>
                                                </td>
                                                <td>
                                                    <input type="hidden" name="sub_items[{{ $loop->index }}][is_custom]" value="{{ $subItem->is_custom }}">
                                                    <span>{{ $subItem->is_custom ? "Yes" : "No"  }}</span>
                                                </td>
                                                <td>
                                                    <input type="hidden" name="sub_items[{{ $loop->index }}][status]" value="{{ $subItem->status }}">
                                                    <span>{{ $subItem->status }}</span>
                                                </td>
                                                <td style="text-align: center">
                                                    <input type="file" name="sub_items[{{ $loop->index }}][image]" class="sub-item-image-file" data-index="{{ $loop->index }}" style="display: none;">
                                                    <img src="{{ $subItem->getFirstMediaUrl('images') ?? '' }}" alt="Sub Item Image" class="sub-item-image-preview" style="max-width: 100px;border-radius: 10px;">
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-danger remove-sub-item" data-id="{{ $subItem->id }}">Remove</button>
                                                    <button type="button" class="btn btn-primary edit-sub-item" data-id="{{ $subItem->id }}">Edit</button>
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
                        <input type="hidden" id="sub_item_index_modal">
                        <div class="mb-3">
                            <label for="sub_item_name_modal" class="form-label">Sub Item Name</label>
                            <input type="text" class="form-control" id="sub_item_name_modal" name="sub_item_name_modal">
                        </div>
                        <div class="mb-3">
                            <label for="sub_item_description_modal" class="form-label">Sub Item Description</label>
                            <textarea class="form-control" id="sub_item_description_modal" name="sub_item_description_modal"></textarea>
                        </div>
                        @if(isset($subItem))
                            <div class="mb-3">
                                <label for="sub_item_amount_modal" class="form-label">Sub Item Amount</label>
                                <input type="number" class="form-control" id="sub_item_amount_modal"
                                       name="sub_item_amount_modal" required {{ $subItem->external_id ? 'readonly disabled' : '' }}>
                            </div>
                            <div class="mb-3">
                                <label for="sub_item_price_modal" class="form-label">Sub Item Price</label>
                                <input type="number" step="0.1" class="form-control"
                                       id="sub_item_price_modal" name="sub_item_price_modal" required {{ $subItem->external_id ? 'readonly disabled' : '' }}>
                            </div>

                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="sub_item_is_custom_modal" name="sub_item_is_custom_modal"
                                    {{ $subItem->is_custom ? 'checked' : '' }} value="{{$subItem->is_custom}}" {{ $subItem->external_id ? 'readonly disabled' : '' }} >
                                <label class="form-check-label" for="sub_item_is_custom_modal">Custom</label>
                            </div>
                            <div id="customFields" class="row" style="{{ !$subItem->is_custom ? 'display: none;' : '' }}">
                                <div class="mb-3 col-sm-12 col-md-6">
                                    <label for="sub_item_minimum_amount_modal" class="form-label">Minimum Amount</label>
                                    <input type="number" class="form-control" id="sub_item_minimum_amount_modal" name="sub_item_minimum_amount_modal" value="{{$subItem->minimum_amount}}" {{ $subItem->external_id ? 'readonly disabled' : '' }} >
                                </div>
                                <div class="mb-3 col-sm-12 col-md-6">
                                    <label for="sub_item_max_amount_modal" class="form-label">Maximum Amount</label>
                                    <input type="number" class="form-control" id="sub_item_max_amount_modal" name="sub_item_max_amount_modal" value="{{$subItem->max_amount}}" {{ $subItem->external_id ? 'readonly disabled' : '' }} >
                                </div>
                            </div>

                        @endif

                        <div class="mb-3 col-sm-12 col-md-12">
                            <label for="sub_item_sub_status_modal" class="form-label" {{ $subItem->external_id ? 'readonly disabled' : '' }} >Status</label>

                            <select class="form-control" id="sub_item_sub_status_modal" name="sub_item_sub_status_modal" {{ $subItem->external_id ? 'readonly disabled' : '' }} >
                                <option value="active" {{$subItem->status == 'active' ? 'selected': ''}} selected>Active</option>
                                <option value="inactive" {{ $subItem->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="sub_item_image_modal" class="form-label">Sub Item Image</label>
                            <input type="file" class="form-control" id="sub_item_image_modal" name="sub_item_image_modal" accept="image/*">
                            <div class="mt-2" id="sub-item-image-preview-modal"></div>
                        </div>
                        <button type="submit" class="btn btn-primary">Save Sub Item</button>
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
            $('#sub_item_is_custom_modal').change(function() {
                console.log("custom val", $(this).is(':checked'))
                if ($(this).is(':checked')) {
                    $('#customFields').show();
                } else {
                    $('#customFields').hide();
                }
            });
            // Image previews
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

            // Preview image when selected in the modal
            $('#sub_item_image_modal').change(function() {
                let reader = new FileReader();
                reader.onload = function(e) {
                    $('#sub-item-image-preview-modal').html('<img src="' + e.target.result + '" alt="Sub Item Image Preview" style="max-width: 200px;">');

                    // Update the table image preview immediately
                    if ($('#sub_item_index_modal').val()) {
                        let subItemIndex = $('#sub_item_index_modal').val();
                        let subItemRow = $('#sub-items-table-body').find('tr[data-id="' + subItemIndex + '"]');
                        subItemRow.find('.sub-item-image-preview').attr('src', e.target.result);
                        subItemRow.find('input[name^="sub_items"][name$="[image_url]"]').val(e.target.result);
                    }
                }
                reader.readAsDataURL(this.files[0]);
            });

            // Handle sub-item form submission
            $('#create-sub-item-form').on('submit', function(e) {
                e.preventDefault();
                let subItemName = $('#sub_item_name_modal').val();
                let subItemDescription = $('#sub_item_description_modal').val();
                let subItemAmount = $('#sub_item_amount_modal').val();
                let subItemPrice = $('#sub_item_price_modal').val();

                let subItemMinAmount = $('#sub_item_minimum_amount_modal').val();
                let subItemMaxAmount = $('#sub_item_max_amount_modal').val();
                let subItemSubStatus = $('#sub_item_sub_status_modal').val();
                let subItemSubIsCustom = $('#sub_item_is_custom_modal').is(':checked') ? 1 : 0;

                console.log("subItemSubIsCustom", subItemSubIsCustom)

                let subItemImage = $('#sub_item_image_modal')[0].files[0];
                let subItemIndex = $('#sub_item_index_modal').val();

                let reader = new FileReader();
                reader.onload = function(event) {
                    let imageSrc = event.target.result;

                    if (subItemIndex) {
                        // Edit existing sub-item
                        let subItemRow = $('#sub-items-table-body').find('tr[data-id="' + subItemIndex + '"]');
                        subItemRow.find('input[name^="sub_items"][name$="[name]"]').val(subItemName);
                        subItemRow.find('input[name^="sub_items"][name$="[description]"]').val(subItemDescription);
                        subItemRow.find('input[name^="sub_items"][name$="[amount]"]').val(subItemAmount);
                        subItemRow.find('input[name^="sub_items"][name$="[price]"]').val(subItemPrice);

                        subItemRow.find('input[name^="sub_items"][name$="[minimum_amount]"]').val(subItemMinAmount);
                        subItemRow.find('input[name^="sub_items"][name$="[max_amount]"]').val(subItemMaxAmount);
                        subItemRow.find('input[name^="sub_items"][name$="[status]"]').val(subItemSubStatus);
                        subItemRow.find('input[name^="sub_items"][name$="[is_custom]"]').val(subItemSubIsCustom);

                        subItemRow.find('input[name^="sub_items"][name$="[image_url]"]').val(imageSrc);
                        subItemRow.find('span').eq(0).text(subItemName);
                        subItemRow.find('span').eq(1).text(subItemDescription);
                        subItemRow.find('span').eq(2).text(subItemAmount);
                        subItemRow.find('span').eq(3).text(subItemPrice);

                        subItemRow.find('span').eq(4).text(subItemMinAmount);
                        subItemRow.find('span').eq(5).text(subItemMaxAmount);
                        subItemRow.find('span').eq(6).text(subItemSubStatus);

                        if (subItemImage) {
                            subItemRow.find('.sub-item-image-preview').attr('src', imageSrc);

                            let imageData = new DataTransfer();
                            imageData.items.add(subItemImage);
                            subItemRow.find('.sub-item-image-file').prop('files', imageData.files);
                        }
                    } else {
                        // Add new sub-item
                        let subItemCount = $('#sub-items-table-body tr').length;
                        let subItemRow = `
                            <tr data-id="${subItemCount}">
                                <td>
                                    <input type="hidden" name="sub_items[${subItemCount}][name]" value="${subItemName}">
                                    <span>${subItemName}</span>
                                </td>
                                <td>
                                    <input type="hidden" name="sub_items[${subItemCount}][description]" value="${subItemDescription}">
                                    <span>${subItemDescription}</span>
                                </td>
                                <td>
                                    <input type="hidden" name="sub_items[${subItemCount}][amount]" value="${subItemAmount}">
                                    <span>${subItemAmount}</span>
                                </td>
                                <td>
                                    <input type="hidden" name="sub_items[${subItemCount}][price]" value="${subItemPrice}">
                                    <span>${subItemPrice}</span>
                                </td>

                                <td>
                                    <input type="hidden" name="sub_items[${subItemCount}][minimum_amount]" value="${subItemMinAmount}">
                                    <span>${subItemMinAmount}</span>
                                </td>
                                <td>
                                    <input type="hidden" name="sub_items[${subItemCount}][max_amount]" value="${subItemMaxAmount}">
                                    <span>${subItemMaxAmount}</span>
                                </td>
                                 <td>
                                    <input type="hidden" name="sub_items[${subItemCount}][is_custom]" value="${subItemSubIsCustom}">
                                    <span>${subItemSubIsCustom ? "Yes" : "No"}</span>
                                </td>
                                <td>
                                    <input type="hidden" name="sub_items[${subItemCount}][status]" value="${subItemSubStatus}">
                                    <span>${subItemSubStatus}</span>
                                </td>


                                <td>
                                    <input type="hidden" name="sub_items[${subItemCount}][image_url]" value="${imageSrc}">
                                    <input type="file" name="sub_items[${subItemCount}][image]" class="sub-item-image-file" data-index="${subItemCount}" style="display: none;">
                                    <img src="${imageSrc}" alt="Sub Item Image" class="sub-item-image-preview" style="max-width: 100px;border-radius: 10px;">
                                </td>
                                <td>
                                    <button type="button" class="btn btn-danger remove-sub-item" data-id="${subItemCount}">Remove</button>
                                    <button type="button" class="btn btn-primary edit-sub-item" data-id="${subItemCount}">Edit</button>
                                </td>
                            </tr>
                        `;
                        $('#sub-items-table-body').append(subItemRow);

                        if (subItemImage) {
                            let imageData = new DataTransfer();
                            imageData.items.add(subItemImage);
                            $('#sub-items-table-body').find('tr:last .sub-item-image-file').prop('files', imageData.files);
                        }
                    }

                    // Clear modal form fields
                    $('#create-sub-item-form')[0].reset();
                    $('#sub_item_index_modal').val('');
                    $('#sub-item-image-preview-modal').html('');
                    $('#subItemModal').modal('hide');
                };

                if (subItemImage) {
                    reader.readAsDataURL(subItemImage);
                } else {
                    reader.onload({ target: { result: '' } }); // Trigger onload manually if no image is selected
                }
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
                            text: 'Item updated successfully.',
                            icon: 'success',
                            loader: true,
                            loaderBg: '#f96868',
                            position: 'top-right',
                            hideAfter: 3000000,
                            afterHidden: function () {
                                window.location.href = "{{ route('items.index') }}";
                            }
                        });

                        // Optionally, reset the form fields
                        // $('#create-edit-item-form')[0].reset();
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

            $(document).on('click', '.edit-sub-item', function() {
                let subItemRow = $(this).closest('tr');
                let subItemIndex = subItemRow.data('id');
                let subItemName = subItemRow.find('input[name^="sub_items"][name$="[name]"]').val();
                let subItemDescription = subItemRow.find('input[name^="sub_items"][name$="[description]"]').val();
                let subItemAmount = subItemRow.find('input[name^="sub_items"][name$="[amount]"]').val();
                let subItemPrice = subItemRow.find('input[name^="sub_items"][name$="[price]"]').val();

                let subItemMaxAmount = subItemRow.find('input[name^="sub_items"][name$="[max_amount]"]').val();
                let subItemMinAmount = subItemRow.find('input[name^="sub_items"][name$="[minimum_amount]"]').val();

                let subItemStatus = subItemRow.find('input[name^="sub_items"][name$="[status]"]').val();
                let subItemIsCustom = subItemRow.find('input[name^="sub_items"][name$="[is_custom]"]').val();



                console.log("subItemIsCustom", subItemIsCustom)
                // console.log("subItemStatus1", subItemRow.html())


                $('#create-sub-item-form')[0].reset();  // Reset form fields

                $('#sub_item_name_modal').val(subItemName);
                $('#sub_item_description_modal').val(subItemDescription);
                $('#sub_item_amount_modal').val(subItemAmount);
                $('#sub_item_price_modal').val(subItemPrice);
                $('#sub_item_index_modal').val(subItemIndex);

                $('#sub_item_max_amount_modal').val(subItemMaxAmount);
                $('#sub_item_minimum_amount_modal').val(subItemMinAmount);
                // $('#sub_item_sub_status_modal').val(subItemStatus);
                $('#sub_item_is_custom_modal').val(subItemIsCustom);

                let subItemImageSrc = subItemRow.find('.sub-item-image-preview').attr('src');
                if (subItemImageSrc) {
                    $('#sub-item-image-preview-modal').html('<img src="' + subItemImageSrc + '" alt="Sub Item Image Preview" style="max-width: 200px;">');
                } else {
                    $('#sub-item-image-preview-modal').html('<img src="" alt="Sub Item Image Preview" style="max-width: 200px;">');
                }

                $('#sub_item_image_modal').off('change').on('change', function() {
                    let reader = new FileReader();
                    reader.onload = function(e) {
                        $('#sub-item-image-preview-modal').html('<img src="' + e.target.result + '" alt="Sub Item Image Preview" style="max-width: 200px;">');
                        subItemRow.find('.sub-item-image-preview').attr('src', e.target.result);
                        subItemRow.find('input[name^="sub_items"][name$="[image_url]"]').val(e.target.result);

                        let imageData = new DataTransfer();
                        imageData.items.add($('#sub_item_image_modal')[0].files[0]);
                        subItemRow.find('.sub-item-image-file').prop('files', imageData.files);
                    }
                    reader.readAsDataURL(this.files[0]);
                });

                $('#subItemModal').modal('show');
            });

            $('#subItemModal').on('hidden.bs.modal', function () {
                $('#create-sub-item-form')[0].reset();  // Reset form fields when the modal is closed
                $('#sub-item-image-preview-modal').html('');
                $('#sub_item_image_modal').off('change');
            });
        });
    </script>
@endsection
