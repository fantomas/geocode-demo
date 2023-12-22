<template>
  <div id="app">
      <h1>Find an address</h1>
      <table class="maps">
        <tr>
          <td>
            <input v-model="textAddress" placeholder="Add text address" />
          </td>
          <td>
            <button v-on:click="geocodeOSMAddress">Geocode with OSM</button>
            <button v-on:click="geocodeGoogleAddress">Get Google Coordinates</button>
          </td>
        </tr>
        <tr>
          <td>
            OSM Coordinates: {{ latlngOSM }}
            <l-map style="height: 700px" :zoom="zoom" :center="center" ref="osmMap" @ready="doSomethingOnReady()">
              <l-tile-layer :url="url" :attribution="attribution"></l-tile-layer>
              <l-marker :lat-lng="markerLatLng"></l-marker>
            </l-map>
          </td>
          <td>
            Google Coordinates: {{ latlngGoogle }}
            <l-map style="height: 700px" :zoom="zoom" :center="center" ref="googleMap" @ready="doSomethingOnReady()">
              <l-tile-layer :url="url" :attribution="attribution"></l-tile-layer>
              <l-marker :lat-lng="markerLatLng"></l-marker>
            </l-map>
          </td>
        </tr>
      </table>
  </div>
</template>

<script>
import axios from 'axios'

import L from 'leaflet';
import { LMap, LTileLayer, LMarker } from 'vue2-leaflet';
import { Icon } from 'leaflet';

let spinnerOpts = {lines: 15, length: 60, width: 7, radius: 35, scale: 1.5, corners: 1, animation: 'spinner-line-fade-more', color: '#1974c8'};

delete Icon.Default.prototype._getIconUrl;
Icon.Default.mergeOptions({
  iconRetinaUrl: require('leaflet/dist/images/marker-icon-2x.png'),
  iconUrl: require('leaflet/dist/images/marker-icon.png'),
  shadowUrl: require('leaflet/dist/images/marker-shadow.png'),
});

export default {
  name: 'app',
  components: {
    LMap,
    LTileLayer,
    LMarker
  },
  data () {
    return {
      url: 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
      attribution:
        '&copy; <a target="_blank" href="http://osm.org/copyright">OpenStreetMap</a> contributors',
      zoom: 17,
      center: [51.505, -0.159],
      markerLatLng: [51.504, -0.159],
      osMap: L,
      textAddress: null,
      latlngOSM: null,
      latlngGoogle: null,
      apiResponse: null,
    }
  },
  methods: {
    doSomethingOnReady() {
      this.mapOSM = this.$refs.osmMap.mapObject;
      this.mapGoogle = this.$refs.googleMap.mapObject;
    },
    async geocodeOSMAddress() {
      this.mapOSM.spin(true, spinnerOpts);
      const { data } = await axios.get('http://localhost:82/api/geocode/osm?q=' + this.textAddress);
      this.apiResponse = data;

      this.mapOSM.spin(false);
      let latlng = L.latLng(this.apiResponse.latitude, this.apiResponse.longitude);

      if(typeof latlng !== 'undefined') {
        this.latlngOSM = JSON.stringify(latlng, null, "  ");
        L.marker(latlng).addTo(this.mapOSM).bindTooltip(this.apiResponse.name).openTooltip();
        this.mapOSM.flyTo(latlng, 17);
      } else {
        this.latlngOSM = 'Not found';
      }
    },
    async geocodeGoogleAddress() {
      this.mapGoogle.spin(true, spinnerOpts);
      const { data } = await axios.get('http://localhost:82/api/geocode/google?q=' + this.textAddress);
      this.apiResponse = data;

      this.mapGoogle.spin(false);
      let latlng = L.latLng(this.apiResponse.latitude, this.apiResponse.longitude);

      if(typeof latlng !== 'undefined') {
        this.latlngGoogle = JSON.stringify(latlng, null, "  ");
        L.marker(latlng).addTo(this.mapGoogle).bindTooltip(this.apiResponse.name).openTooltip();
        this.mapGoogle.panTo(latlng, 17);
      } else {
        this.latlngGoogle = 'Not found';
      }
    },
    beforeMount() {

    },
  }
}
</script>

<style scoped>
h1 {
  margin: 40px 0 0;
}
table.maps {
  width: 100%;
}
table.maps td {
  vertical-align: top;
  width: 50%;
}
table.maps button {
  padding: 5px 10px;
  margin: 10px 0;
}
table.maps input {
  padding: 5px 10px;
  margin: 10px 0;
  width: -webkit-fill-available;
}
@keyframes spinner-line-fade-default {
  0%, 100% {
    opacity: 0.22; /* minimum opacity */
  }
  1% {
    opacity: 1;
  }
}

</style>
