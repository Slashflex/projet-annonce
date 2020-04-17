$(document).ready(function() {
    // Hide the flash message popup
    setInterval(function() {
        $('.flash-notice').slideUp().fadeOut();
    }, 3000)

    // Displays images as carousel indicators
    $('.carousel-indicators > li').each(function(e) {
        $(this).append($(".carousel-item  > img[data-idimage="+e+"]").clone());
    })
});
