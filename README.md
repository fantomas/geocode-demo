# Geocoding SPA app with Vue and REST API
_A technical task for a job interview_

## Project Request
**Problem to solve:** Retrieve and display coordinates (longitude and latitude) for an address, using Google Maps and OpenStreetMap (OSM). Show results from both Google Maps and OSM.


## Description
The project is realised with the following tech stack:
 - Frontend: Vue (2.7.15), Docker
 - Backend: Symfony components (6.4.1), PHP 8.3, Docker 

The frontend is calling one endpoint for geocoding in the REST API backend. From there the business logic can call different providers (as OSM, Google, Esri, etc.) and will return an unified response. The communication between the Frontend and the Backend is synchronous.

![UI](project_image.png?raw=true "UI")


## Installation
1. Clone the project from the repo
    ```
    git clone git@github.com:fantomas/geocode-demo.git
    ```
2. create`rest-app/.env.local` file with `GOOGLE_API_KEY=[registered-google-api-key]`
3. run docker containers `docker-compose up -d`

## Notes
1. you can access the frontend SPA on http://localhost/
2. you can access the backend REST API on http://localhost:82/api/geocode/osm?q=ndk+bulgaria
3. only OSM and Google providers are integrated and working. The rest as Esri, Here or Gisgraphy are prepared, but with tougher registrations, so they are not connected
4. a good option would be to use one visual map, where I place different colours of markers for the different providers. This may be version 2