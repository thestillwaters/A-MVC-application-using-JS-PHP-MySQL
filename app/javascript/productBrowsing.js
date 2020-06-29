
$(function() {
    var ajaxRequest;
    let browseForm = $('#browseForm');
    let allInputs = $(':input');
    let browseFormSerialized;

    allInputs.change(function()
    {
        browseFormSerialized = browseForm.serialize();
        browseForm.submit();
    });

    browseForm.submit(function(event) {
        event.preventDefault();
        if(ajaxRequest) {
            ajaxRequest.abort();
        }
        ajaxRequest = $.ajax({
            url:'/browsed',
            type:'POST',
            data:browseFormSerialized
        });
        //productBrowsing.phtml has browseResult id
        ajaxRequest.done(function(respose){
            $('#browseResult').html(respose);
        });
    });
});