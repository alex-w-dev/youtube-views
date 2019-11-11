<?php
function getMyDir($path) {
  return pathinfo($path)['dirname'];
}