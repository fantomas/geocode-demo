FROM node:lts-alpine

# bind your app to the gateway IP
ENV HOST=0.0.0.0

RUN npm install -g @vue/cli

WORKDIR /var/www/spa-app/

# copy both 'package.json' and 'package-lock.json' (if available)
COPY spa-app/package*.json ./

# install project dependencies
RUN npm install

# copy project files and folders to the current working directory (i.e. 'app' folder)
#COPY spa-app/. .

# build app for production with minification
#RUN npm run build
#RUN npm install -g http-server

EXPOSE 8080
#CMD [ "http-server", "dist" ]
CMD ["npm", "run", "dev"]
#CMD ["npm", "run", "build"]