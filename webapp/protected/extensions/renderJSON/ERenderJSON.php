<?php
  class ERenderJSON extends CApplicationComponent
  {
    Const C404NOTFOUND = 'HTTP/1.1 404 Not Found';
    Const C401UNAUTHORIZED = 'HTTP/1.1 401 Unauthorized';
    Const C406NOTACCEPTABLE = 'HTTP/1.1 406 Not Acceptable';
    Const C201CREATED = 'HTTP/1.1 201 Created';
    Const C200OK = 'HTTP/1.1 200 OK';
    Const C500INTERNALSERVERERROR = 'HTTP/1.1 500 Internal Server Error';  
    
    /**
   * Get HTTP Status Headers From code
   */ 
  public function getHttpStatus($statusCode, $default='C200OK')
  {
    switch ($statusCode) {
      case '200':
        return self::C200OK;
        break;
      case '201':
        return self::C201CREATED;
        break;
      case '401':
        return self::C401UNAUTHORIZED;
        break;
      case '404':
        return self::C404NOTFOUND;
        break;
      case '406':
        return self::C406NOTACCEPTABLE;
        break;
      case '500':
        return self::C500INTERNALSERVERERROR;
        break;
      default:
        return self::$default;
    }
  }

    public function renderJSON($data, $statusCode='200')
    { 
      $controller = new CController(__class__);
      $controller->renderPartial('ext.renderJSON.views.api.output', array('HTTPStatus'=>$this->getHttpStatus($statusCode), 'data'=>$data));
    }
  }
