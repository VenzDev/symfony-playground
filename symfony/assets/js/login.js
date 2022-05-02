import $ from 'jquery';
import {showSpinner} from "./components/Utils";

$('form').submit(function() {
    showSpinner('.btn');
})
