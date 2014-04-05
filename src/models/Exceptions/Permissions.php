<?php namespace Pulpitum\Auth\Models\Exceptions;

class PermissionNotFoundException extends  \OutOfBoundsException {}
class PermissionExistsException extends \UnexpectedValueException {}
class NameRequiredException extends \UnexpectedValueException {}
class ValueRequiredException extends \UnexpectedValueException {}