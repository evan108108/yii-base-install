<?php
  @header('Content-type: application/json');
  @header($HTTPStatus);
  echo trim(CJSON::encode($data));
