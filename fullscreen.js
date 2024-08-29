$(document).ready(function() {
    // Function to open image in fullscreen with transition
    function openFullscreenImage(imgSrc) {
        var overlay = $("<div id='image-overlay'></div>").css({
            position: "fixed",
            top: "0",
            left: "0",
            width: "100%",
            height: "100%",
            background: "rgba(0, 0, 0, 0.8)",
            display: "flex",
            justifyContent: "center",
            alignItems: "center",
            zIndex: "999"
        });

        var fullscreenImage = $("<img>").attr("src", imgSrc).addClass("fullscreen-image").css({
            maxWidth: "90%",
            maxHeight: "90%",
            cursor: "pointer",
            opacity: "0" // Start with zero opacity
        });

        overlay.append(fullscreenImage);
        $("body").append(overlay);

        // Apply transition
        fullscreenImage.animate({
            opacity: 1
        }, 500);

        // Close fullscreen image on click
        overlay.click(function() {
            fullscreenImage.animate({
                opacity: 0
            }, 300, function() {
                overlay.remove();
            });
        });
    }
    // Add click event listener to the image with class "profile_pic"

    $(".profile_pic").on("click", function() {
        var imgSrc = $(this).attr("src");
        openFullscreenImage(imgSrc);
    });

    $(".item_img").on("click", function() {
        var imgSrc = $(this).attr("src");
        openFullscreenImage(imgSrc);
    });
});
