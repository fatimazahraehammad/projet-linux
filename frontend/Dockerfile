

FROM node:20-alpine AS build



WORKDIR /app



COPY package*.json ./



RUN npm install



COPY . .
<<<<<<< Updated upstream



RUN npm run build



FROM nginx:alpine



COPY --from=build /app/dist/frontend/browser /usr/share/nginx/html


EXPOSE 80

=======
RUN npm run build
FROM nginx:alpine

COPY --from=build /app/dist/frontend/browser /usr/share/nginx/html
EXPOSE 80
>>>>>>> Stashed changes
CMD ["nginx", "-g", "daemon off;"]
