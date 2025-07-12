# Project Hezekiah

This project demonstrates a modern web application architecture featuring a powerful **FilamentPHP-based Content Management System (CMS)** as the backend, providing a robust API, and a fast, flexible **Astro-based frontend** for rendering content. The entire stack is containerized using **Docker Compose** for easy setup and development.

---

## 🚀 Project Overview

This application serves as a content platform where administrators can manage various types of pages and associated featured links through an intuitive Filament admin panel. The content is then consumed by an Astro frontend, which dynamically renders pages using **Server-Side Rendering (SSR)** to ensure content freshness without requiring a frontend rebuild on every backend update.

---

## 💡 Key Technologies

### Backend:

* **Laravel**: PHP Framework for the API and CMS logic.
* **FilamentPHP**: A TALL stack-based admin panel for Laravel, providing an elegant interface for content management (Pages, Featured Links).
* **PostgreSQL/MySQL**: Database for storing content.
* **Nginx**: Web server for the Laravel backend.

### Frontend:

* **Astro**: A modern static site builder that supports various rendering strategies (SSG, SSR, Hybrid). Configured for SSR in this project for dynamic content.
* **Tailwind CSS**: A utility-first CSS framework for rapid and responsive UI development.
* **Vanilla JavaScript**: Used for dynamic content loading animations within specific layouts.

### Containerization:

* **Docker & Docker Compose**: For defining and running multi-container Docker applications, ensuring a consistent development and production environment.

---

## 📁 Project Structure

The project is organized into distinct services managed by Docker Compose:

* `backend/`: Contains the Laravel application, including:
    * Filament Admin Panel
    * API endpoints for pages (`/api/pages`) and featured links (`/api/pages/{id}/featured`).
    * Database migrations and models (`Page`, `FeaturedLink`).
* `frontend/`: Contains the Astro application, including:
    * `src/pages/[slug].astro`: The dynamic route for rendering individual pages.
    * `src/layouts/BaseLayout.astro`: The foundational HTML structure.
    * `src/layouts/BasicPageLayout.astro`: A general-purpose layout for standard content pages.
    * `src/layouts/FeaturePageLayout.astro`: A specialized layout for "feature" type pages, including unique styling and dynamic featured links.
    * `src/components/`: Reusable Astro components (e.g., Header, Footer, Container).
    * `public/`: Static assets like `styles.css` and `favicon.svg`.
* `docker-compose.yml`: Defines the services (backend, frontend, database, Nginx) and their configurations.

---

## ✨ Core Features

### 1. Filament CMS (Backend)

* **Page Management**: Create, edit, and delete various types of pages.
* **Homepage Flag**: A homepage toggle to designate a specific page as the site's homepage.
* **Page Types**: Pages can be assigned a type (e.g., `basic`, `feature`) using ToggleButtons in the admin form.
  * **Featured Links (for Feature Pages)**:
    * A **Repeater field** on "feature" type pages allows administrators to add multiple "Featured Links".
    * Each featured link has an **internal name, a public-facing label, and a URL**.
      
### 2. Astro Frontend (SSR)

* **Dynamic Page Routing**: The `src/pages/[slug].astro` route handles all content pages dynamically.
* **Server-Side Rendering (SSR)**:
    * Astro is configured with `output: 'server'` and `@astrojs/node` adapter.
    * Page content and metadata are fetched from the Laravel API (`http://web:80/api/pages/{slug}`) on the server-side for each request. This ensures content is always fresh without needing a frontend rebuild.
* **Conditional Layouts**:
    * `[slug].astro` dynamically selects `BasicPageLayout` or `FeaturePageLayout` based on the `type` property of the fetched page data.
* **Featured Links Display**:
    * `FeaturePageLayout.astro` fetches featured links for the specific page (`/api/pages/{pageId}/featured`).
    * These links are displayed as a responsive 2-column grid of buttons between the page title and the main content.
* **Animations & Styling**:
    * `FeaturePageLayout.astro` applies specific CSS animations (fade-in, scale-up) and a serif font to the page title and content.
    * Animations for dynamically loaded content (the main article block) are triggered by a `MutationObserver` within `FeaturePageLayout.astro`'s client-side script, ensuring they play even if content is injected after initial render. This avoids using `:global()` selectors for these animations.
* **Static Asset Handling**: Global styles (`styles.css`) are served from the `public/` directory, bypassing dynamic routing.

---

## ⚙️ Setup Instructions

1.  **Clone the Repository**: (Assuming a project root containing `backend/`, `frontend/`, and `docker-compose.yml`)

    ```bash
    git clone <your-repo-url>
    cd <your-project-root>
    ```

2.  **Environment Configuration**:

    * **Backend (`backend/.env`)**:

        ```env
        APP_NAME="Filament CMS"
        APP_ENV=local
        APP_KEY= # Generate with `php artisan key:generate`
        APP_DEBUG=true
        APP_URL=http://localhost:8000

        DB_CONNECTION=pgsql # Or mysql
        DB_HOST=db
        DB_PORT=5432 # Or 3306 for MySQL
        DB_DATABASE=your_database_name
        DB_USERNAME=your_db_user
        DB_PASSWORD=your_db_password

        # ... other Laravel configs
        ```

    * **Frontend (`frontend/.env`)**: (Not strictly needed for this setup as API URLs are hardcoded in Astro files for server-side fetches, but good practice for client-side environment variables if used)

        ```env
        # Example:
        PUBLIC_API_URL="http://localhost:8000/api"
        ```

3.  **Build and Run Docker Containers**:

    ```bash
    docker-compose up -d --build
    ```

    This will build the images and start the `nginx`, `backend`, `db`, and `frontend` services.

4.  **Install Backend Dependencies & Migrate Database**:

    ```bash
    docker-compose exec backend composer install
    docker-compose exec backend php artisan migrate --seed # --seed to populate initial data
    docker-compose exec backend php artisan storage:link # If you use file uploads
    ```

5.  **Install Frontend Dependencies**:

    ```bash
    docker-compose exec frontend npm install
    ```

6.  **Generate Laravel Application Key**:

    ```bash
    docker-compose exec backend php artisan key:generate
    ```

---

## 🚀 Usage

### Filament Admin Panel:

* Access the admin panel at `http://localhost:8000/admin`.
* Log in with the credentials created by your database seeder (e.g., `php artisan make:filament-user`).
* Navigate to "Pages" to create and manage your content. Remember to set `type` to "Feature" for pages where you want to add "Featured Links".

### Astro Frontend:

* Access the frontend at `http://localhost:4321/`. <- defaults to the homepage
* Navigate to your pages (e.g., `http://localhost:4321/{slug}`). Content updates made in Filament will reflect immediately on page refresh without rebuilding the Astro app.

---

## 🚧 Future Improvements / Considerations

* **More Page Types**: Expand `PageTypesEnum` and create more specialized layouts (e.g., `MediaPageLayout`, `CollectionPageLayout`).
* **Collections**: Create subpages/articles that can be categorised and viewed from a `Collection` page type.
* **Media uploading**: User can upload images, videos or other files to the backend. Using tags/categories you can choose to display for specified pages.
* **Frontend Error Handling**: More robust error pages (e.g., custom 404 page in Astro) when API fetches fail.
* **Deployment Automation**: Set up CI/CD pipelines to automatically rebuild and deploy the Astro frontend whenever content is updated in Filament.
* **WebSSO**: Using Microsoft or Google SSO, authenticate or create new users to the platform.
* **Add support for external integrations** Use this as the centralised platform for your organisation's users.
