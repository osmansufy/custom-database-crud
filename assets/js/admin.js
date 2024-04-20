jQuery(document).ready(function($) {
    $('.cdc-delete-button').on('click', function() {
        const recordId = $(this).data('record-id');
        $deleteButton = $(this);
        if (confirm('Are you sure you want to delete this record?')) {
            $.ajax({
                url: cdc_ajax_object.ajax_url,
                type: 'POST',
                data: {
                    action: 'delete_record',
                    record_id: recordId,
                    security: cdc_ajax_object.ajax_nonce
                },
                success: function(response) {
                    console.log(response);
                    // Handle success
                    if (response.success) {
                    //  remove the row from the table
                        $deleteButton.closest('tr').remove();

                        // Show a success message
                        $('.cdc-db-item-list').prepend('<div class="notice notice-success is-dismissible"><p>Record deleted successfully.</p></div>');
                    } else {
                        console.error(response.data);
                    }
                },
                error: function(xhr, status, error) {
                    // Handle error
                $('.cdc-db-item-list').html('<p>Something went wrong. Please try again later.</p>');
                }
            });
        }
    });
});
