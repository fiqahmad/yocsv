<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;

class NotificationService
{
    public function notifyAdmins(string $type, string $title, string $message, array $data = []): void
    {
        $admins = User::where('is_admin', true)->get();

        foreach ($admins as $admin) {
            $this->createNotification($admin, $type, $title, $message, $data);
        }
    }

    public function notifyUser(User $user, string $type, string $title, string $message, array $data = []): void
    {
        $this->createNotification($user, $type, $title, $message, $data);
    }

    public function csvUploaded(User $uploader, int $csvUploadId, string $fileName): void
    {
        // Notify all admins about the new upload
        $this->notifyAdmins(
            'csv_uploaded',
            'New CSV Upload',
            "{$uploader->name} uploaded a new CSV file: {$fileName}",
            [
                'csv_upload_id' => $csvUploadId,
                'file_name' => $fileName,
                'uploader_id' => $uploader->id,
                'uploader_name' => $uploader->name,
            ]
        );

        // Notify the user that their upload is being processed
        $this->notifyUser(
            $uploader,
            'csv_processing',
            'CSV Upload Received',
            "Your file {$fileName} has been received and is being processed.",
            [
                'csv_upload_id' => $csvUploadId,
                'file_name' => $fileName,
            ]
        );
    }

    public function csvProcessed(User $uploader, int $csvUploadId, string $fileName, array $stats): void
    {
        $message = "File {$fileName} processed successfully. ";
        $message .= "Total: {$stats['total_rows']}, ";
        $message .= "Inserted: {$stats['inserted_rows']}, ";
        $message .= "Updated: {$stats['updated_rows']}, ";
        $message .= "Errors: {$stats['error_rows']}";

        // Notify the user
        $this->notifyUser(
            $uploader,
            'csv_completed',
            'CSV Processing Complete',
            $message,
            [
                'csv_upload_id' => $csvUploadId,
                'file_name' => $fileName,
                'stats' => $stats,
            ]
        );

        // Notify admins
        $this->notifyAdmins(
            'csv_completed',
            'CSV Processing Complete',
            "{$uploader->name}'s file {$fileName} has been processed. {$stats['inserted_rows']} inserted, {$stats['updated_rows']} updated.",
            [
                'csv_upload_id' => $csvUploadId,
                'file_name' => $fileName,
                'uploader_id' => $uploader->id,
                'uploader_name' => $uploader->name,
                'stats' => $stats,
            ]
        );
    }

    public function csvFailed(User $uploader, int $csvUploadId, string $fileName, string $error): void
    {
        // Notify the user
        $this->notifyUser(
            $uploader,
            'csv_failed',
            'CSV Processing Failed',
            "Failed to process {$fileName}: {$error}",
            [
                'csv_upload_id' => $csvUploadId,
                'file_name' => $fileName,
                'error' => $error,
            ]
        );

        // Notify admins
        $this->notifyAdmins(
            'csv_failed',
            'CSV Processing Failed',
            "{$uploader->name}'s file {$fileName} failed to process: {$error}",
            [
                'csv_upload_id' => $csvUploadId,
                'file_name' => $fileName,
                'uploader_id' => $uploader->id,
                'uploader_name' => $uploader->name,
                'error' => $error,
            ]
        );
    }

    protected function createNotification(User $user, string $type, string $title, string $message, array $data = []): Notification
    {
        return Notification::create([
            'user_id' => $user->id,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'data' => $data,
        ]);
    }
}
