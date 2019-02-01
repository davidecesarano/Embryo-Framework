<?php 

    /**
     * MethodNotAllowedException
     * 
     * This class extends the routing "method not allowed" exception for use it into
     * app controller's.
     * 
     * @author Davide Cesarano <davide.cesarano@unipegaso.it>
     * @link https://github.com/davidecesarano/embryo-framework
     */
    
    namespace Embryo\Exceptions;

    use Embryo\Routing\Exceptions\MethodNotAllowedException as RoutingMethodNotAllowedException;
    
    class MethodNotAllowedException extends RoutingMethodNotAllowedException {}