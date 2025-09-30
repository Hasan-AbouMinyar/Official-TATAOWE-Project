<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class AddTestProfilePhotos extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Find the user
        $user = User::where('email', 'abouminyar@gmail.com')->first();
        
        if (!$user) {
            $this->command->warn('User not found!');
            return;
        }

        // Create a simple test image (1x1 pixel transparent PNG)
        $imageData = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==');
        
        // Ensure the directory exists
        $directory = storage_path('app/public/profile-photos');
        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }
        
        // Generate filename
        $filename = 'test-' . $user->id . '.png';
        $path = 'profile-photos/' . $filename;
        
        // Store the image
        Storage::disk('public')->put($path, $imageData);
        
        // Update user photo
        $user->update([
            'photo' => $path
        ]);
        
        $this->command->info('Test photo added for user: ' . $user->name);
        $this->command->info('Photo path: ' . $path);
    }
}
