<?php
// Post management functions

function getAllPosts($db, $publishedOnly = true) {
    try {
        $sql = "SELECT p.*, c.name as category_name, c.slug as category_slug, c.color as category_color, 
                       u.username as author_name
                FROM posts p
                LEFT JOIN categories c ON p.category_id = c.id
                LEFT JOIN users u ON p.author_id = u.id";
        
        if ($publishedOnly) {
            $sql .= " WHERE p.published = 1";
        }
        
        $sql .= " ORDER BY p.created_at DESC";
        
        $stmt = $db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    } catch(PDOException $e) {
        error_log("Get Posts Error: " . $e->getMessage());
        return [];
    }
}

function getPostBySlug($db, $slug) {
    try {
        $stmt = $db->prepare("
            SELECT p.*, c.name as category_name, c.slug as category_slug, c.color as category_color,
                   u.username as author_name
            FROM posts p
            LEFT JOIN categories c ON p.category_id = c.id
            LEFT JOIN users u ON p.author_id = u.id
            WHERE p.slug = :slug AND p.published = 1
            LIMIT 1
        ");
        $stmt->execute(['slug' => $slug]);
        return $stmt->fetch();
    } catch(PDOException $e) {
        error_log("Get Post Error: " . $e->getMessage());
        return null;
    }
}

function getPostById($db, $id) {
    try {
        $stmt = $db->prepare("
            SELECT p.*, c.name as category_name, c.slug as category_slug, c.color as category_color
            FROM posts p
            LEFT JOIN categories c ON p.category_id = c.id
            WHERE p.id = :id
            LIMIT 1
        ");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    } catch(PDOException $e) {
        error_log("Get Post Error: " . $e->getMessage());
        return null;
    }
}

function createPost($db, $data, $authorId) {
    try {
        $stmt = $db->prepare("
            INSERT INTO posts (title, slug, excerpt, content, image_url, category_id, author_id, published)
            VALUES (:title, :slug, :excerpt, :content, :image_url, :category_id, :author_id, :published)
        ");
        
        return $stmt->execute([
            'title' => $data['title'],
            'slug' => $data['slug'],
            'excerpt' => $data['excerpt'],
            'content' => $data['content'],
            'image_url' => $data['image_url'] ?? null,
            'category_id' => $data['category_id'] ?? null,
            'author_id' => $authorId,
            'published' => $data['published'] ?? 0
        ]);
    } catch(PDOException $e) {
        error_log("Create Post Error: " . $e->getMessage());
        return false;
    }
}

function updatePost($db, $id, $data) {
    try {
        $stmt = $db->prepare("
            UPDATE posts 
            SET title = :title, slug = :slug, excerpt = :excerpt, content = :content, 
                image_url = :image_url, category_id = :category_id, published = :published
            WHERE id = :id
        ");
        
        return $stmt->execute([
            'id' => $id,
            'title' => $data['title'],
            'slug' => $data['slug'],
            'excerpt' => $data['excerpt'],
            'content' => $data['content'],
            'image_url' => $data['image_url'] ?? null,
            'category_id' => $data['category_id'] ?? null,
            'published' => $data['published'] ?? 0
        ]);
    } catch(PDOException $e) {
        error_log("Update Post Error: " . $e->getMessage());
        return false;
    }
}

function deletePost($db, $id) {
    try {
        $stmt = $db->prepare("DELETE FROM posts WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    } catch(PDOException $e) {
        error_log("Delete Post Error: " . $e->getMessage());
        return false;
    }
}

function generateSlug($title) {
    $slug = strtolower($title);
    $slug = preg_replace('/[^a-z0-9\s-]/', '', $slug);
    $slug = preg_replace('/[\s-]+/', '-', $slug);
    $slug = trim($slug, '-');
    return $slug;
}

function getAllCategories($db) {
    try {
        $stmt = $db->prepare("SELECT * FROM categories ORDER BY name ASC");
        $stmt->execute();
        return $stmt->fetchAll();
    } catch(PDOException $e) {
        error_log("Get Categories Error: " . $e->getMessage());
        return [];
    }
}
