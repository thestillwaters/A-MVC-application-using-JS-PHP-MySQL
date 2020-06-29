$(function() {
    let ajaxRequest;
    let searchFormSerialized;
    //keyup() is an inbuilt method in jQuery to trigger the keyup event
    // whenever User releases a key from the keyboard
    // productSearching.phtml has searchBox id
    $('#searchBox').keyup(function() {
        let searchForm = $('#searchForm');
        searchFormSerialized = searchForm.serialize();
        searchForm.submit();
    });
    /// productSearching.phtml has searchForm id
    $('#searchForm').submit(function(event) {
        event.preventDefault();
        if(ajaxRequest) {
            ajaxRequest.abort();
        }
        ajaxRequest = $.ajax({
            url:'/searched',
            type:'POST',
            data:searchFormSerialized
        });
        ajaxRequest.done(function(respose){
            $('#searchResult').html(respose);
        });
    });
});