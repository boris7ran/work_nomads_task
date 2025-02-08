FROM node:22-alpine

WORKDIR /app

COPY ../../webapp/package.json /app/package.json
RUN npm install -g pnpm
RUN pnpm i

CMD ["pnpm", "dev"]