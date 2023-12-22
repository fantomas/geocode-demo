<?php

namespace App\Controller;

use App\Dto\googleResponse;
use App\Dto\osmResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[Route('/api', name: 'api_')]
class GeocodeController extends AbstractController
{
    #[Route('/geocode/{type}', name: 'geocode_osm', requirements: ['type' => 'osm'], methods: ['get'])]
    public function geocode_osm(HttpClientInterface $osmClient, Request $request, SerializerInterface $serializer): JsonResponse
    {
        $q = $request->query->get('q');
        $path = 'search';
        $response = $osmClient->request('GET', $path, [
            'query' => [
                'format' => 'json',
                'q' => $q,
            ],
        ]);

        $results = $response->toArray();
        $singleResultArr = array_shift($results);
        $singleResultJson = $serializer->serialize($singleResultArr, 'json');

        $osmResponse = $serializer->deserialize($singleResultJson, osmResponse::class, 'json');

        return $this->json($osmResponse);
    }

    #[Route('/geocode/{type}', name: 'geocode_gisg', requirements: ['type' => 'gisg'], methods: ['get'])]
    public function geocode_gisg(HttpClientInterface $gisgClient, Request $request): JsonResponse
    {
        $q = $request->query->get('q');
        $path = 'geocoding/geocode';
        $response = $gisgClient->request('GET', $path, [
            'query' => [
                'address' => $q,
            ],
        ]);

        $results = $response->toArray(); //todo
        $singleResult = array_shift($results);

        return $this->json($singleResult);
    }

    #[Route('/geocode/{type}', name: 'geocode_here', requirements: ['type' => 'here'], methods: ['get'])]
    public function geocode_here(HttpClientInterface $hereClient, Request $request): JsonResponse
    {
        $q = $request->query->get('q');
        $path = 'geocode';
        $response = $hereClient->request('GET', $path, [
            'query' => [
                'apiKey' => '',
                'q' => $q,
            ],
        ]);

        $results = $response->toArray();
        $singleResult = array_shift($results); //todo

        return $this->json($singleResult);
    }

    #[Route('/geocode/{type}', name: 'geocode_google', requirements: ['type' => 'google'], methods: ['get'])]
    public function geocode_google(HttpClientInterface $googleClient, Request $request, SerializerInterface $serializer, NormalizerInterface $normalizer): JsonResponse
    {
        $q = $request->query->get('q');
        $path = 'geocode/json';
        $response = $googleClient->request('GET', $path, [
            'query' => [
                'key' => $this->getParameter('app.maps_api_keys.google'),
                'address' => $q,
            ],
        ]);

        $results = $response->toArray();
        $singleResultArr = array_shift($results['results']);

        $singleResultJson = $serializer->serialize($singleResultArr, 'json');

        $googleResponse = $serializer->deserialize($singleResultJson, googleResponse::class, 'json');

        //return $this->json($googleResponse); // todo returns back the nested path, which is not desired.
        return $this->json(['latitude' => $googleResponse->latitude, 'longitude' => $googleResponse->longitude, 'name' => $googleResponse->formatted_address]);
    }

    #[Route('/geocode/{type}', name: 'geocode_esri', requirements: ['type' => 'esri'], methods: ['get'])]
    public function geocode_esri(HttpClientInterface $esriClient, Request $request): JsonResponse
    {
        $q = $request->query->get('q');
        $path = 'findAddressCandidates';
        $response = $esriClient->request('GET', $path, [
            'query' => [
                'token' => '',
                'address' => $q,
                'outFields' => '*',
                'f' => 'json',
            ],
        ]);

        $results = $response->toArray();
        $singleResult = array_shift($results); //todo

        return $this->json($singleResult);
    }
}
