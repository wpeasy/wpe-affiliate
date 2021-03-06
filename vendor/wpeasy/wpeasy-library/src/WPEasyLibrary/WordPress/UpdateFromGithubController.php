<?php


namespace WPEasyLibrary\WordPress;

/**
 * Class UpdateFromGithubController
 * @package WPEasyLibrary\WordPress
 *
 */
class UpdateFromGithubController
{
    private $slug; // plugin slug
    private $pluginData; // plugin data
    private $username; // GitHub username
    private $repo; // GitHub repo name
    private $pluginFile; // __FILE__ of our plugin
    private $githubAPIResult; // holds data from GitHub
    private $accessToken; // GitHub private repo token

    /**
     * UpdateFromGithubController constructor.
     * @param $pluginFile
     * @param $gitHubUsername
     * @param $repo
     * @param string $accessToken
     *
     * $pluginFile WordPress Plugin file, used to determine plugin details
     * Usage:
     *if ( is_admin() ) {
    * new UpdateController( __FILE__, 'github-user', "repository" );
    * }
     *
     */
    function __construct($pluginFile, $gitHubUsername, $repo, $accessToken = '' ) {
        add_filter( "pre_set_site_transient_update_plugins", array( $this, "setTransitent" ) );
        add_filter( "plugins_api", array( $this, "setPluginInfo" ), 10, 3 );
        add_filter( "upgrader_post_install", array( $this, "postInstall" ), 10, 3 );

        $this->pluginFile = $pluginFile;
        $this->username = $gitHubUsername;
        $this->repo = $repo;
        $this->accessToken = $accessToken;

    }

    // Get information regarding our plugin from WordPress
    private function initPluginData() {
        $this->slug = plugin_basename( $this->pluginFile );
        $this->pluginData = get_plugin_data( $this->pluginFile );
    }

    // Get information regarding our plugin from GitHub
    private function getRepoReleaseInfo() {
        // Only do this once
        if ( ! empty( $this->githubAPIResult ) ) {
            return;
        }

        // Query the GitHub API
        $url = "https://api.github.com/repos/{$this->username}/{$this->repo}/releases";

        // We need the access token for private repos
        if ( ! empty( $this->accessToken ) ) {
            $url = add_query_arg( array( "access_token" => $this->accessToken ), $url );
        }

        // Get the results
        $this->githubAPIResult = wp_remote_retrieve_body( wp_remote_get( $url ) );
        if ( ! empty( $this->githubAPIResult ) ) {
            $this->githubAPIResult = @json_decode( $this->githubAPIResult );
        }

        // Use only the latest release
        if ( is_array( $this->githubAPIResult ) ) {
            $this->githubAPIResult = $this->githubAPIResult[0];
            $this->githubAPIResult->tag_name = preg_replace("/[a-zA-Z]/", "", $this->githubAPIResult->tag_name ); //remove version text
        }

        if(WP_DEBUG) file_put_contents(__DIR__ . '/repo-details.json', json_encode($this->githubAPIResult), JSON_PRETTY_PRINT);
    }

    // Push in plugin version information to get the update notification
    public function setTransitent( $transient ) {
        //temp test
        /*
        $file = __DIR__ . '/transient-' . time() . '.json';
        file_put_contents($file, json_encode($transient));
        */

        //|| !isset($transient->checked[$this->slug])


        // If we have checked the plugin data before, don't re-check
        if ( empty( $transient->checked ) ) {
            return $transient;
        }

        //file_put_contents(__DIR__ . '/checked-' .time() . '.txt', json_encode($transient->checked ));

        // Get plugin & GitHub release information
        $this->initPluginData();
        $this->getRepoReleaseInfo();

        // Check the versions if we need to do an update
        $doUpdate = version_compare( $this->githubAPIResult->tag_name, @$transient->checked[$this->slug] );

        if(WP_DEBUG) file_put_contents(__DIR__ . '/doUpdate.json', json_encode(
            [
                'tag_name' => $this->githubAPIResult->tag_name,
                'checked'=> @$transient->checked[$this->slug]
            ]
        ), JSON_PRETTY_PRINT);

        // Update the transient to include our updated plugin data
        if ( $doUpdate == 1 ) {
            $package = $this->githubAPIResult->zipball_url;

            // Include the access token for private GitHub repos
            if ( !empty( $this->accessToken ) ) {
                $package = add_query_arg( array( "access_token" => $this->accessToken ), $package );
            }

            $obj = new \stdClass();
            $obj->slug = $this->slug;
            $obj->new_version = $this->githubAPIResult->tag_name;
            $obj->url = $this->pluginData["PluginURI"];
            $obj->package = $package;
            $transient->response[$this->slug] = $obj;
        }


        if(WP_DEBUG) file_put_contents(__DIR__ . '/transient.json', json_encode($transient), JSON_PRETTY_PRINT);
        return $transient;
    }

    // Push in plugin version information to display in the details lightbox
    public function setPluginInfo( $false, $action, $response ) {
        // Get plugin & GitHub release information
        $this->initPluginData();
        $this->getRepoReleaseInfo();

        // If nothing is found, do nothing
        if ( empty( $response->slug ) || $response->slug != $this->slug ) {
            return false;
        }

        // Add our plugin information
        $response->last_updated = $this->githubAPIResult->published_at;
        $response->slug = $this->slug;
        $response->plugin_name  = $this->pluginData["Name"];
        $response->name  = $this->pluginData["Name"];
        $response->version = $this->githubAPIResult->tag_name;
        $response->author = $this->pluginData["AuthorName"];
        $response->homepage = $this->pluginData["PluginURI"];

        // This is our release download zip file
        $downloadLink = $this->githubAPIResult->zipball_url;

        // Include the access token for private GitHub repos
        if ( !empty( $this->accessToken ) ) {
            $downloadLink = add_query_arg(
                array( "access_token" => $this->accessToken ),
                $downloadLink
            );
        }
        $response->download_link = $downloadLink;

        // Create tabs in the lightbox
        $response->sections = array(
            'description' => $this->pluginData["Description"],
            'changelog' => \Parsedown::instance()->parse( $this->githubAPIResult->body )
        );

        // Gets the required version of WP if available
        $matches = null;
        preg_match( "/requires:\s([\d\.]+)/i", $this->githubAPIResult->body, $matches );
        if ( ! empty( $matches ) ) {
            if ( is_array( $matches ) ) {
                if ( count( $matches ) > 1 ) {
                    $response->requires = $matches[1];
                }
            }
        }

        // Gets the tested version of WP if available
        $matches = null;
        preg_match( "/tested:\s([\d\.]+)/i", $this->githubAPIResult->body, $matches );
        if ( ! empty( $matches ) ) {
            if ( is_array( $matches ) ) {
                if ( count( $matches ) > 1 ) {
                    $response->tested = $matches[1];
                }
            }
        }

        return $response;
    }

    // Perform additional actions to successfully install our plugin
    public function postInstall( $true, $hook_extra, $result ) {
        // Get plugin information
        $this->initPluginData();

        // Remember if our plugin was previously activated
        $wasActivated = is_plugin_active( $this->slug );

        // Since we are hosted in GitHub, our plugin folder would have a dirname of

        // reponame-tagname change it to our original one:
        global $wp_filesystem;
        $pluginFolder = WP_PLUGIN_DIR . DIRECTORY_SEPARATOR . dirname( $this->slug );
        $wp_filesystem->move( $result['destination'], $pluginFolder );
        $result['destination'] = $pluginFolder;

        // Re-activate plugin if needed
        if ( $wasActivated ) {
            $activate = activate_plugin( $this->slug );
        }

        return $result;
    }
}
