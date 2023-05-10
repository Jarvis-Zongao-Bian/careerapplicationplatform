<?php
/*
 * Escape HTML for output
 */

function escape($html) {
    return htmlspecialchars($html, ENT_QUOTES|ENT_SUBSTITUTE, "UTF-8");
}

function gotoPage($url) {
    echo "<script>window.location.href = ".$url."</script>";
}