import $ from "jquery";

const showSpinner = (btn) => {
    $(btn).addClass('disabled');
    $(btn).children(":first-child").removeClass("d-none");
}

export {showSpinner};