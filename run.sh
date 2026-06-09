#!/bin/bash

# Control script for Laravel Sail blog project

show_help() {
    echo "Usage: ./run.sh [command]"
    echo ""
    echo "Commands:"
    echo "  start     Start Docker containers and build assets"
    echo "  stop      Stop Docker containers"
    echo "  restart   Restart Docker containers"
    echo "  migrate   Run database migrations"
    echo "  seed      Run database seeders"
    echo "  test      Run application tests"
    echo "  dev       Run Vite development server"
    echo "  status    Show status of Docker containers"
    echo "  logs      Show logs for Laravel container"
    echo "  shell     Enter the Laravel container shell"
}

case "$1" in
    start)
        echo "Starting Docker containers..."
        ./vendor/bin/sail up -d
        echo "Running npm run build..."
        ./vendor/bin/sail npm run build
        echo "Project started successfully!"
        echo "Access it at http://localhost"
        ;;
    stop)
        echo "Stopping Docker containers..."
        ./vendor/bin/sail down
        ;;
    restart)
        echo "Restarting Docker containers..."
        ./vendor/bin/sail restart
        ;;
    migrate)
        echo "Running migrations..."
        ./vendor/bin/sail artisan migrate
        ;;
    seed)
        echo "Running database seeders..."
        ./vendor/bin/sail artisan db:seed
        ;;
    test)
        echo "Running tests..."
        ./vendor/bin/sail test
        ;;
    dev)
        echo "Starting Vite development server..."
        ./vendor/bin/sail npm run dev
        ;;
    status)
        ./vendor/bin/sail ps
        ;;
    logs)
        ./vendor/bin/sail logs
        ;;
    shell)
        ./vendor/bin/sail shell
        ;;
    *)
        show_help
        ;;
esac
