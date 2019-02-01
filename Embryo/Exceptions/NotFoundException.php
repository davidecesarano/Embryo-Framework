<?php 

    /**
     * NotFoundException
     * 
     * This class extends the routing "not found" exception for use it into
     * app controller's.
     * 
     * @author Davide Cesarano <davide.cesarano@unipegaso.it>
     * @link https://github.com/davidecesarano/embryo-framework
     */
    
    namespace Embryo\Exceptions;

    use Embryo\Routing\Exceptions\NotFoundException as RoutingNotFoundException;
    
    class NotFoundException extends RoutingNotFoundException {}