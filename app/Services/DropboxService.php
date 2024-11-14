<?php

namespace App\Services;

use Spatie\Dropbox\Client;
use GuzzleHttp\Client as HttpClient;
use League\Flysystem\Filesystem;
use Spatie\FlysystemDropbox\DropboxAdapter;
use Illuminate\Support\Facades\Log;
use Exception;


class DropboxService
{
    private $accessToken;

    public function __construct(string $accessToken = null)
    {
        $this->accessToken = $accessToken ?? config('filesystems.disks.dropbox.authorization_token');
        
        // Check and refresh the access token if it's invalid
        if (!$this->isAccessTokenValid()) {
            $this->refreshAccessToken();
        }
    }

    /**
     * Check if the current access token is valid.
     *
     * @return bool
     */
    private function isAccessTokenValid(): bool
    {
        // Simple check to verify if the current token works.
        try {
            $this->getClient()->getAccountInfo(); // Try to access the account to check token validity
            return true;
        } catch (Exception $e) {
            Log::warning('Dropbox access token is invalid or expired: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Refresh the Dropbox access token using the client ID and client secret.
     * 
     * @return string New access token
     * @throws Exception if unable to refresh the access token
     */
    private function refreshAccessToken()
    {
        Log::warning('Dropbox access token expired. Attempting to refresh token.');

        $httpClient = new HttpClient();

        // Request to refresh the token using the client_id and client_secret
        $response = $httpClient->post('https://api.dropboxapi.com/oauth2/token', [
            'form_params' => [
                'grant_type'    => 'client_credentials',
                'client_id'     => config('filesystems.disks.dropbox.key'),
                'client_secret' => config('filesystems.disks.dropbox.secret'),
            ],
        ]);

        // Decode the response to get the new access token
        $data = json_decode($response->getBody(), true);

        if (isset($data['access_token'])) {
            $this->accessToken = $data['access_token'];
            Log::info('Dropbox access token refreshed successfully.');

            // Store the new token where appropriate (e.g., database, config, etc.)
            // Example: Auth::user()->update(['dropbox_token' => $this->accessToken]);

            return $this->accessToken;
        }

        // If no token is returned, throw an error
        throw new Exception('Failed to refresh Dropbox access token. No token received.');
    }

    /**
     * Get the Dropbox Client.
     * 
     * @return Client
     */
    public function getClient(): Client
    {
        // Return a new Dropbox Client instance using the current valid access token
        return new Client($this->accessToken);
    }

    /**
     * Get the list of contents from Dropbox.
     *
     * @param string $directoryName
     * @return array
     * @throws Exception
     */
    public function getListing(string $directoryName): array
    {
        try {
            $client = $this->getClient();
            $adapter = new DropboxAdapter($client);
            $filesystem = new Filesystem($adapter, ['case_sensitive' => false]);

            // Fetch the contents of the directory from Dropbox
            $contents = $filesystem->listContents($directoryName, false);
            
            return collect($contents)
                ->map(fn($item) => [
                    'name' => $item->path(),
                    'type' => $item->type(),
                ])
                ->toArray();
        } catch (Exception $e) {
            Log::error('Failed to get Dropbox listing: ' . $e->getMessage());
            throw new Exception('Error fetching Dropbox listing');
        }
    }
}
