# Manifests

This is where the Kubernetes manifest files will be, from the deployments, Helm charts (if needed), Ingresses, and other things. Use `kubectl apply -f [file.yml]|[file.yaml]` to deploy. Keep in mind, in case there is an update, change the version of the container image, and then reapply the changed file.
