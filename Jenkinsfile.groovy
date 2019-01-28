node {
    checkout scm

    def customImage

    stage('Build image') {
        customImage = docker.build("my-image:${env.BUILD_ID}")
    }

    stage('Run tests') {
        customImage.inside('--network szeremi -e DB_HOST=mysql -e APP_ENV=testing') {
            sh 'cd /var/www && php artisan migrate:fresh && vendor/bin/phpunit'
        }
    }

    stage('Deploy') {
        // Re-tag the image, because the deployment server is the same as the Jenkins server.
        sh "docker tag ${customImage.id} amcsi/youtube-delete-tracker"
    }
}
