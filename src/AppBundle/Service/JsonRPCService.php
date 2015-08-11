<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 11.08.2015
 * Time: 11:49
 */

namespace AppBundle\Service;


use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class JsonRPCService
{
    const ID = 'app.jsonrpc';
    const PARSE_ERROR = -32700;
    const INVALID_REQUEST = -32600;
    const METHOD_NOT_FOUND = -32601;
    const INVALID_PARAMS = -32602;
    const INTERNAL_ERROR = -32603;

    /**
     * Functions that are allowed to be called
     *
     * @var array $functions
     */
    private $functions = array();

    public function __construct($configuration)
    {
        $this->functions = $configuration;
    }


    public function execute(Request $httprequest)
    {

        $json = $httprequest->getContent();
        $request = json_decode($json, true);
       // var_dump($request);die();

        $requestId = (isset($request['id']) ? $request['id'] : null);
        if ($request === null) {
            return 'errorrrr';
        }

      //  if (isset($this->functions['functions'][$request['method']])) {
            $method = $this->functions['functions'][$request['method']]['method'];
            $servicename = $this->functions['functions'][$request['method']]['service'];

      //  } else {
       //     return 'error';
     //   }

        try {
            $service = $this->container->get($servicename);
        } catch (ServiceNotFoundException $e) {
            return $this->getErrorResponse(self::METHOD_NOT_FOUND, $requestId);
        }
        $params = (isset($request['params']) ? $request['params'] : array());

        if (is_callable(array($service, $method))) {
            $r = new \ReflectionMethod($service, $method);
            $rps = $r->getParameters();

            if (is_array($params)) {
                if (!(count($params) >= $r->getNumberOfRequiredParameters()
                    && count($params) <= $r->getNumberOfParameters())
                ) {
                    return $this->getErrorResponse(self::INVALID_PARAMS, $requestId,
                        sprintf('Number of given parameters (%d) does not match the number of expected parameters (%d required, %d total)',
                            count($params), $r->getNumberOfRequiredParameters(), $r->getNumberOfParameters()));
                }

            }
            if ($this->isAssoc($params)) {
                $newparams = array();
                foreach ($rps as $i => $rp) {
                    /* @var \ReflectionParameter $rp */
                    $name = $rp->name;
                    if (!isset($params[$rp->name]) && !$rp->isOptional()) {
                        return $this->getErrorResponse(self::INVALID_PARAMS, $requestId,
                            sprintf('Parameter %s is missing', $name));
                    }
                    if (isset($params[$rp->name])) {
                        $newparams[] = $params[$rp->name];
                    } else {
                        $newparams[] = null;
                    }
                }
                $params = $newparams;
            }

            // correctly deserialize object parameters
            foreach ($params as $index => $param) {
                // if the json_decode'd param value is an array but an object is expected as method parameter,
                // re-encode the array value to json and correctly decode it using jsm_serializer
                if (is_array($param) && !$rps[$index]->isArray() && $rps[$index]->getClass() != null) {
                    $class = $rps[$index]->getClass()->getName();
                    $param = json_encode($param);
                    $params[$index] = $this->container->get('jms_serializer')->deserialize($param, $class, 'json');
                }
            }

            try {
                $result = call_user_func_array(array($service, $method), $params);
            } catch (\Exception $e) {
                return $this->getErrorResponse(self::INTERNAL_ERROR, $requestId, $e->getMessage());
            }

            $response = array('jsonrpc' => '2.0');
            $response['result'] = $result;
            $response['id'] = $requestId;

            if ($this->container->has('jms_serializer')) {
                $functionConfig = (
                isset($this->functions[$request['method']])
                    ? $this->functions[$request['method']]
                    : array()
                );
                $serializationContext = $this->getSerializationContext($functionConfig);
                $response = $this->container->get('jms_serializer')->serialize($response, 'json', $serializationContext);
            } else {
                $response = json_encode($response);
            }

            return new Response($response, 200, array('Content-Type' => 'application/json'));
        } else {
            return $this->getErrorResponse(self::METHOD_NOT_FOUND, $requestId);
        }
    }
}