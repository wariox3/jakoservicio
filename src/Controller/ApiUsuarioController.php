<?php

namespace App\Controller;
use App\Classes\Utilidades;
use App\Entity\Usuario;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ApiUsuariosController
 * @package App\Controller}
 */
class ApiUsuarioController extends FOSRestController {

    /**
     * @return array
     * @Rest\Post("/v1/usuario/lista")
     */
    public function lista(Request $request) {
        try {
            $raw = json_decode($request->getContent(), true);
            $em = $this->getDoctrine()->getManager();
            return $em->getRepository(Usuario::class)->lista($raw);
        } catch (\Exception $e) {
            return [
                'error' => true,
            ];
        }
    }

    /**
     * @return array
     * @Rest\Post("/v1/usuario/validar")
     */
    public function validar(Request $request) {
        try {
            $em = $this->getDoctrine()->getManager();
            $raw = json_decode($request->getContent(), true);
            return $em->getRepository(Usuario::class)->validar($raw, $this->get("publicador"));
        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * @return array
     * @Rest\Post("/v1/usuario/verificar")
     */
    public function verificar(Request $request) {
        try {
            $em = $this->getDoctrine()->getManager();
            $raw = json_decode($request->getContent(), true);
            return $em->getRepository(Usuario::class)->verificar($raw);
        } catch (\Exception $e) {
            return [
                'error' => true,
            ];
        }
    }

    /**
     * @param Request $request
     * @Rest\Post("/v1/usuario/informacion")
     * @return array|mixed
     */
    public function informacionUsuario(Request $request) {
        try {
            $em = $this->getDoctrine()->getManager();
            $raw = json_decode($request->getContent(), true);
            return $em->getRepository(Usuario::class)->informacionUsuario($raw);
        } catch (\Exception $e) {
            return [
                'error' => true,
            ];
        }
    }

    /**
     * Esta función permite guardar el token generado por firebase para un usuario.
     * @Rest\Post("/v1/usuario/guardar/fcm-token")
     */
    public function guardarTokenFCM(Request $request) {
        try {
            $em = $this->getDoctrine()->getManager();
            $raw = json_decode($request->getContent(), true);
            return $em->getRepository(Usuario::class)->guardarFCMToken($raw);
        } catch (\Exception $e) {
            return [
                'error' => true,
            ];
        }
    }

    /**
     * @Rest\Post("/v1/usuario/invitar")
     */
    public function invitarAJako(Request $request) {
        try {
            $raw = json_decode($request->getContent(), true);
            $telefonos = $raw['telefonos']?? false;
            $jugador = $raw['jugador']?? 0;
            if(!$telefonos && !$jugador) {
                return [
                    'error_controlado' => 'No se proporcionaron los datos',
                ];
            }
            $this->get("msgTxt")->invitarAInstalar($jugador, $telefonos);
            return true;
        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => $e->getMessage(),
            ];
        }
    }

}