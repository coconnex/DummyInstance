pipeline {
    agent any
    environment {
        staging_server = "3.9.225.94"  
        remote_base_dir = "/home2/coheziatest"  // Set the desired remote base directory here
    }
    stages {
        stage('Deploy to Remote') {
            steps {
                script {
                    // Find files modified in the last 10 minutes
                    def filesToDeploy = sh(script: "find ${env.WORKSPACE} -type f -mmin -10 | grep -v '.git' | grep -v 'Jenkinsfile'", returnStdout: true).trim().split("\n")
                    
                    // Check if files were found
                    if (filesToDeploy.size() > 0) {
                        // Deploy each file to the remote server
                        filesToDeploy.each { fileName ->
                            // Clean the file path and remove the job name part
                            def fil = fileName.replaceAll("${JOB_NAME}", "").trim()

                            // Construct the full remote file path using the new remote base directory
                            def remoteFilePath = "${remote_base_dir}${fil}"

                            // Deploy using SCP
                            try {
                                // Make sure the file paths are correct and that $WORKSPACE is correctly interpolated
                                sh """
                                    scp -r ${WORKSPACE}${fil} root@${staging_server}:${remoteFilePath}
                                """
                                echo "Successfully deployed: ${fil}"
                            } catch (Exception e) {
                                echo "Error deploying file: ${fil}"
                                currentBuild.result = 'FAILURE'
                            }
                        }
                    } else {
                        echo "No files modified in the last 10 minutes. Nothing to deploy."
                    }
                }
            }
        }
    }
}
