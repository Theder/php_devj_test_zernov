$(document).ready(function() {
    $(document).keydown(function(event) {
        let key = event.key;

        switch(key) {
            case "ArrowUp":
            case "ArrowDown":
            case "ArrowLeft":
            case "ArrowRight":
                alert(key);
                break;
        }
    });
});