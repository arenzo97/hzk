# Use Node.js LTS with glibc (no Alpine)
FROM node:18-slim

# Set working directory
WORKDIR /app

# Install system dependencies (if needed)
RUN apt-get update && apt-get install -y git && rm -rf /var/lib/apt/lists/*

# Copy package files and install deps
COPY package*.json ./
RUN npm install

# Copy app source
COPY . .

# Expose Astro dev port
EXPOSE 4321

# Start dev server with --host for Docker
CMD ["npm", "run", "dev", "--", "--host"]