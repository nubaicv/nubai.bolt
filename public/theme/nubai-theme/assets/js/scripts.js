//scripts

$(document).ready( function(){
    
    $('#main-nav-button').click(function(){
        $('#main-nav-wrapper').toggleClass('w3-hide');
    });
    
    $('#show-customer-menu-small').click(function(){
        
        $('#customer-menu-small').toggleClass('w3-show');
    });
    
    $('#show-localeswitcher-small').click(function(){
        
        $('#localeswitcher-small').toggleClass('w3-show');
    });
});