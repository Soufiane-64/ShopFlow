<?php

namespace ShopFlow\Services;

class DocumentationService
{
    public function generateApiDocs(array $endpoints): string
    {
        $markdown = "# ShopFlow API Documentation\n\n";
        $markdown .= "Base URL: `https://api.shopflow.dev/v1`\n\n";
        $markdown .= "## Authentication\n\n";
        $markdown .= "All API requests require authentication via JWT token:\n\n";
        $markdown .= "```\nAuthorization: Bearer {your_token}\n```\n\n";
        $markdown .= "## Endpoints\n\n";
        
        foreach ($endpoints as $category => $routes) {
            $markdown .= "### " . ucfirst($category) . "\n\n";
            foreach ($routes as $method => $description) {
                $markdown .= "- `{$method}` - {$description}\n";
            }
            $markdown .= "\n";
        }
        
        return $markdown;
    }

    public function generateProjectDocs(array $project): string
    {
        $markdown = "# Project: {$project['name']}\n\n";
        $markdown .= "**Type:** {$project['type']}\n\n";
        $markdown .= "**Status:** {$project['status']}\n\n";
        $markdown .= "## Description\n\n";
        $markdown .= $project['description'] ?? 'No description provided';
        $markdown .= "\n\n## Team Members\n\n";
        
        if (!empty($project['team_members'])) {
            foreach ($project['team_members'] as $member) {
                $markdown .= "- {$member['name']} ({$member['email']})\n";
            }
        }
        
        return $markdown;
    }

    public function generateChangeLog(array $releases): string
    {
        $markdown = "# Changelog\n\n";
        
        foreach ($releases as $release) {
            $markdown .= "## [{$release['version']}] - {$release['created_at']}\n\n";
            $markdown .= $release['notes'] ?? 'No release notes';
            $markdown .= "\n\n";
        }
        
        return $markdown;
    }
}
