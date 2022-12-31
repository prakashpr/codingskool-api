<?php
declare(strict_types=1);

use App\Models\DB;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;

return function (App $app) {
    $app->get('/', function (Request $request, Response $response, $args) {
        $response->getBody()->write("Welcome to coding skool!");
        return $response;
    });

    // User APIs
    $app->post('/login', function (Request $request, Response $response, array $args) {
        try {
                $requestBody = $request->getParsedBody();
                $password = md5($requestBody['password']);
                $username = $requestBody['username'];
                $sql = "SELECT * FROM users WHERE user_name =:username AND password =:password AND status = 1";
            
                $db = new Db();
                $conn = $db->connect();
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':username', $username);
                $stmt->bindParam(':password', $password);
                $stmt->execute();
                $user = $stmt->fetch();
                $db = null;
                
                $response->getBody()->write(json_encode($user));
                return $response
                    ->withHeader('content-type', 'application/json')
                    ->withStatus(200);
                } catch (PDOException $e) {
                $error = array(
                    "message" => $e->getMessage()
                );

                $response->getBody()->write(json_encode($error));
                return $response
                    ->withHeader('content-type', 'application/json')
                    ->withStatus(500);
                }
           }
    );

    $app->get('/users/{userID}', function (Request $request, Response $response, array $args) {
        $sql = "SELECT * FROM users WHERE user_id = :userID AND status = 1";
        try {
                $db = new Db();
                $conn = $db->connect();
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':userID', $args['userID']);
                $stmt->execute();
                $user = $stmt->fetch();
                $db = null;
                
                $response->getBody()->write(json_encode($user));
                return $response
                    ->withHeader('content-type', 'application/json')
                    ->withStatus(200);
                } catch (PDOException $e) {
                $error = array(
                    "message" => $e->getMessage()
                );
            
                $response->getBody()->write(json_encode($error));
                return $response
                    ->withHeader('content-type', 'application/json')
                    ->withStatus(500);
                }
       }
    );

    $app->post('/users', function (Request $request, Response $response, array $args) {
        try {
                $requestBody = $request->getParsedBody();
                $password = md5($requestBody['password']);
                $username = $requestBody['username'];
                $email = $requestBody['email'];
                $sql = "INSERT INTO users (user_name, password, email, role_id, status) VALUES (:username, :password, :email, 1, 1)";
            
                $db = new Db();
                $conn = $db->connect();
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':username', $username);
                $stmt->bindParam(':password', $password);
                $stmt->bindParam(':email', $email);
                $result = $stmt->execute();
                $db = null;
                
                $response->getBody()->write(json_encode($result));
                return $response
                    ->withHeader('content-type', 'application/json')
                    ->withStatus(200);
                } catch (PDOException $e) {
                $error = array(
                    "message" => $e->getMessage()
                );

                $response->getBody()->write(json_encode($error));
                return $response
                    ->withHeader('content-type', 'application/json')
                    ->withStatus(500);
                }
           }
    );

    $app->get('/users', function (Request $request, Response $response, array $args) {
        try {
                $sql = "SELECT * FROM users WHERE status = 1";
            
                $db = new Db();
                $conn = $db->connect();
                $stmt = $conn->prepare($sql);
                $stmt->execute();
                $users = $stmt->fetchAll(PDO::FETCH_OBJ);
                $db = null;
                
                $response->getBody()->write(json_encode($users));
                return $response
                    ->withHeader('content-type', 'application/json')
                    ->withStatus(200);
                } catch (PDOException $e) {
                $error = array(
                    "message" => $e->getMessage()
                );

                $response->getBody()->write(json_encode($error));
                return $response
                    ->withHeader('content-type', 'application/json')
                    ->withStatus(500);
                }
           }
    );

    $app->put('/users/{userID}', function (Request $request, Response $response, array $args) {
        try {
                $userID = $args['userID'];
                $requestBody = $request->getParsedBody();
                $username = $requestBody['username'];
                $email = $requestBody['email'];
                $status = $requestBody['status'];
                $sql = "UPDATE users SET user_name = :username, email= :email, status = :status WHERE user_id = $userID";
            
                $db = new Db();
                $conn = $db->connect();
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':username', $username);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':status', $status);
                $result = $stmt->execute();
                $db = null;
                
                $response->getBody()->write(json_encode($result));
                return $response
                    ->withHeader('content-type', 'application/json')
                    ->withStatus(200);
                } catch (PDOException $e) {
                $error = array(
                    "message" => $e->getMessage()
                );

                $response->getBody()->write(json_encode($error));
                return $response
                    ->withHeader('content-type', 'application/json')
                    ->withStatus(500);
                }
           }
    );

    $app->delete('/users/{userID}', function (Request $request, Response $response, array $args) {
        try {
                $userID = $args['userID'];
                $sql = "DELETE FROM users WHERE user_id = $userID";
            
                $db = new Db();
                $conn = $db->connect();
                $stmt = $conn->prepare($sql);
                $result = $stmt->execute();
                $db = null;
                
                $response->getBody()->write(json_encode($result));
                return $response
                    ->withHeader('content-type', 'application/json')
                    ->withStatus(200);
                } catch (PDOException $e) {
                $error = array(
                    "message" => $e->getMessage()
                );

                $response->getBody()->write(json_encode($error));
                return $response
                    ->withHeader('content-type', 'application/json')
                    ->withStatus(500);
                }
           }
    );


    // Pages APIs
    $app->post('/pages', function (Request $request, Response $response, array $args) {
        try {
                $requestBody = $request->getParsedBody();
                $title = $requestBody['title'];
                $slug = $requestBody['slug'];
                $meta_tags = $requestBody['meta_tags'];
                $description = $requestBody['description'];
                $sql = "INSERT INTO pages (title, slug, meta_tags, description, status) VALUES (:title, :slug, :meta_tags, :description, 1)";
            
                $db = new Db();
                $conn = $db->connect();
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':title', $title);
                $stmt->bindParam(':slug', $slug);
                $stmt->bindParam(':meta_tags', $meta_tags);
                $stmt->bindParam(':description', $description);
                $result = $stmt->execute();
                $db = null;
                
                $response->getBody()->write(json_encode($result));
                return $response
                    ->withHeader('content-type', 'application/json')
                    ->withStatus(200);
                } catch (PDOException $e) {
                $error = array(
                    "message" => $e->getMessage()
                );

                $response->getBody()->write(json_encode($error));
                return $response
                    ->withHeader('content-type', 'application/json')
                    ->withStatus(500);
                }
           }
    );

    $app->put('/pages/{pageID}', function (Request $request, Response $response, array $args) {
        try {
                $pageID = $args['pageID'];
                $requestBody = $request->getParsedBody();
                $title = $requestBody['title'];
                $slug = $requestBody['slug'];
                $meta_tags = $requestBody['meta_tags'];
                $description = $requestBody['description'];
                $status = $requestBody['status'];
                $sql = "UPDATE pages SET title = :title, slug= :slug, meta_tags = :meta_tags, description= :description, status= :status WHERE page_id = $pageID";
            
                $db = new Db();
                $conn = $db->connect();
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':title', $title);
                $stmt->bindParam(':slug', $slug);
                $stmt->bindParam(':meta_tags', $meta_tags);
                $stmt->bindParam(':description', $description);
                $stmt->bindParam(':status', $status);
                $result = $stmt->execute();
                $db = null;
                
                $response->getBody()->write(json_encode($result));
                return $response
                    ->withHeader('content-type', 'application/json')
                    ->withStatus(200);
                } catch (PDOException $e) {
                $error = array(
                    "message" => $e->getMessage()
                );

                $response->getBody()->write(json_encode($error));
                return $response
                    ->withHeader('content-type', 'application/json')
                    ->withStatus(500);
                }
           }
    );

    $app->get('/pages/{pageID}', function (Request $request, Response $response, array $args) {
        try {
                $pageID = $args['pageID'];
                $sql = "SELECT * FROM pages WHERE page_id = $pageID";
            
                $db = new Db();
                $conn = $db->connect();
                $stmt = $conn->prepare($sql);
                $stmt->execute();
                $page = $stmt->fetch();
                $db = null;
                
                $response->getBody()->write(json_encode($page));
                return $response
                    ->withHeader('content-type', 'application/json')
                    ->withStatus(200);
                } catch (PDOException $e) {
                $error = array(
                    "message" => $e->getMessage()
                );

                $response->getBody()->write(json_encode($error));
                return $response
                    ->withHeader('content-type', 'application/json')
                    ->withStatus(500);
                }
           }
    );

    $app->get('/pages/slug/{slug}', function (Request $request, Response $response, array $args) {
        try {
                $slug = $args['slug'];
                $sql = "SELECT * FROM pages WHERE slug = :slug AND status = 1";
            
                $db = new Db();
                $conn = $db->connect();
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':slug', $slug);
                $stmt->execute();
                $page = $stmt->fetch();
                $db = null;
                
                $response->getBody()->write(json_encode($page));
                return $response
                    ->withHeader('content-type', 'application/json')
                    ->withStatus(200);
                } catch (PDOException $e) {
                $error = array(
                    "message" => $e->getMessage()
                );

                $response->getBody()->write(json_encode($error));
                return $response
                    ->withHeader('content-type', 'application/json')
                    ->withStatus(500);
                }
           }
    );

    $app->delete('/pages/{pageID}', function (Request $request, Response $response, array $args) {
        try {
                $pageID = $args['pageID'];
                $sql = "DELETE FROM pages WHERE page_id = $pageID";
            
                $db = new Db();
                $conn = $db->connect();
                $stmt = $conn->prepare($sql);
                $result = $stmt->execute();
                $db = null;
                
                $response->getBody()->write(json_encode($result));
                return $response
                    ->withHeader('content-type', 'application/json')
                    ->withStatus(200);
                } catch (PDOException $e) {
                $error = array(
                    "message" => $e->getMessage()
                );

                $response->getBody()->write(json_encode($error));
                return $response
                    ->withHeader('content-type', 'application/json')
                    ->withStatus(500);
                }
           }
    );

    // Category Apis
    $app->post('/category', function (Request $request, Response $response, array $args) {
        try {
                $requestBody = $request->getParsedBody();
                $category_name = $requestBody['category_name'];
                $slug = $requestBody['slug'];
                $description = $requestBody['description'];
                $sql = "INSERT INTO categories (category_name, slug, description, status) VALUES (:category_name, :slug, :description, 1)";
            
                $db = new Db();
                $conn = $db->connect();
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':category_name', $category_name);
                $stmt->bindParam(':slug', $slug);
                $stmt->bindParam(':description', $description);
                $result = $stmt->execute();
                $db = null;
                
                $response->getBody()->write(json_encode($result));
                return $response
                    ->withHeader('content-type', 'application/json')
                    ->withStatus(200);
                } catch (PDOException $e) {
                $error = array(
                    "message" => $e->getMessage()
                );

                $response->getBody()->write(json_encode($error));
                return $response
                    ->withHeader('content-type', 'application/json')
                    ->withStatus(500);
                }
           }
    );

    $app->put('/category/{categoryID}', function (Request $request, Response $response, array $args) {
        try {
                $categoryID = $args['categoryID'];
                $requestBody = $request->getParsedBody();
                $category_name = $requestBody['category_name'];
                $slug = $requestBody['slug'];
                $description = $requestBody['description'];
                $status = $requestBody['status'];
                $sql = "UPDATE categories SET category_name = :category_name, slug= :slug, description= :description, status= :status WHERE category_id = $categoryID";
            
                $db = new Db();
                $conn = $db->connect();
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':category_name', $category_name);
                $stmt->bindParam(':slug', $slug);
                $stmt->bindParam(':description', $description);
                $stmt->bindParam(':status', $status);
                $result = $stmt->execute();
                $db = null;
                
                $response->getBody()->write(json_encode($result));
                return $response
                    ->withHeader('content-type', 'application/json')
                    ->withStatus(200);
                } catch (PDOException $e) {
                $error = array(
                    "message" => $e->getMessage()
                );

                $response->getBody()->write(json_encode($error));
                return $response
                    ->withHeader('content-type', 'application/json')
                    ->withStatus(500);
                }
           }
    );

    $app->get('/category/{categoryID}', function (Request $request, Response $response, array $args) {
        try {
                $categoryID = $args['categoryID'];
                $sql = "SELECT * FROM categories WHERE category_id = $categoryID";
            
                $db = new Db();
                $conn = $db->connect();
                $stmt = $conn->prepare($sql);
                $stmt->execute();
                $page = $stmt->fetch();
                $db = null;
                
                $response->getBody()->write(json_encode($page));
                return $response
                    ->withHeader('content-type', 'application/json')
                    ->withStatus(200);
                } catch (PDOException $e) {
                $error = array(
                    "message" => $e->getMessage()
                );

                $response->getBody()->write(json_encode($error));
                return $response
                    ->withHeader('content-type', 'application/json')
                    ->withStatus(500);
                }
           }
    );

    $app->delete('/category/{categoryID}', function (Request $request, Response $response, array $args) {
        try {
                $categoryID = $args['categoryID'];
                $sql = "DELETE FROM categories WHERE category_id = $categoryID";
            
                $db = new Db();
                $conn = $db->connect();
                $stmt = $conn->prepare($sql);
                $result = $stmt->execute();
                $db = null;
                
                $response->getBody()->write(json_encode($result));
                return $response
                    ->withHeader('content-type', 'application/json')
                    ->withStatus(200);
                } catch (PDOException $e) {
                $error = array(
                    "message" => $e->getMessage()
                );

                $response->getBody()->write(json_encode($error));
                return $response
                    ->withHeader('content-type', 'application/json')
                    ->withStatus(500);
                }
           }
    );

    // Courses Apis
    $app->post('/courses', function (Request $request, Response $response, array $args) {
        try {
                $requestBody = $request->getParsedBody();
                $course_name = $requestBody['course_name'];
                $slug = $requestBody['slug'];
                $description = $requestBody['description'];
                $category_id = $requestBody['category_id'];
                $sql = "INSERT INTO courses (course_name, slug, description, category_id, status) VALUES (:course_name, :slug, :description, :category_id, 1)";
            
                $db = new Db();
                $conn = $db->connect();
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':course_name', $course_name);
                $stmt->bindParam(':slug', $slug);
                $stmt->bindParam(':description', $description);
                $stmt->bindParam(':category_id', $category_id);
                $result = $stmt->execute();
                $db = null;
                
                $response->getBody()->write(json_encode($result));
                return $response
                    ->withHeader('content-type', 'application/json')
                    ->withStatus(200);
                } catch (PDOException $e) {
                $error = array(
                    "message" => $e->getMessage()
                );

                $response->getBody()->write(json_encode($error));
                return $response
                    ->withHeader('content-type', 'application/json')
                    ->withStatus(500);
                }
           }
    );

    $app->put('/courses/{courseID}', function (Request $request, Response $response, array $args) {
        try {
                $courseID = $args['courseID'];
                $requestBody = $request->getParsedBody();
                $course_name = $requestBody['course_name'];
                $slug = $requestBody['slug'];
                $description = $requestBody['description'];
                $category_id = $requestBody['category_id'];
                $status = $requestBody['status'];
                $sql = "UPDATE courses SET course_name = :course_name, slug= :slug, description= :description, category_id= :category_id, status= :status WHERE course_id = $courseID";
            
                $db = new Db();
                $conn = $db->connect();
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':course_name', $course_name);
                $stmt->bindParam(':slug', $slug);
                $stmt->bindParam(':description', $description);
                $stmt->bindParam(':category_id', $category_id);
                $stmt->bindParam(':status', $status);
                $result = $stmt->execute();
                $db = null;
                
                $response->getBody()->write(json_encode($result));
                return $response
                    ->withHeader('content-type', 'application/json')
                    ->withStatus(200);
                } catch (PDOException $e) {
                $error = array(
                    "message" => $e->getMessage()
                );

                $response->getBody()->write(json_encode($error));
                return $response
                    ->withHeader('content-type', 'application/json')
                    ->withStatus(500);
                }
           }
    );

    $app->get('/courses/{courseID}', function (Request $request, Response $response, array $args) {
        try {
                $courseID = $args['courseID'];
                $sql = "SELECT * FROM courses WHERE course_id = $courseID";
            
                $db = new Db();
                $conn = $db->connect();
                $stmt = $conn->prepare($sql);
                $stmt->execute();
                $page = $stmt->fetch();
                $db = null;
                
                $response->getBody()->write(json_encode($page));
                return $response
                    ->withHeader('content-type', 'application/json')
                    ->withStatus(200);
                } catch (PDOException $e) {
                $error = array(
                    "message" => $e->getMessage()
                );

                $response->getBody()->write(json_encode($error));
                return $response
                    ->withHeader('content-type', 'application/json')
                    ->withStatus(500);
                }
           }
    );

    $app->delete('/courses/{courseID}', function (Request $request, Response $response, array $args) {
        try {
                $courseID = $args['courseID'];
                $sql = "DELETE FROM courses WHERE course_id = $courseID";
            
                $db = new Db();
                $conn = $db->connect();
                $stmt = $conn->prepare($sql);
                $result = $stmt->execute();
                $db = null;
                
                $response->getBody()->write(json_encode($result));
                return $response
                    ->withHeader('content-type', 'application/json')
                    ->withStatus(200);
                } catch (PDOException $e) {
                $error = array(
                    "message" => $e->getMessage()
                );

                $response->getBody()->write(json_encode($error));
                return $response
                    ->withHeader('content-type', 'application/json')
                    ->withStatus(500);
                }
           }
    );

    // Chapter Apis
    $app->post('/chapters', function (Request $request, Response $response, array $args) {
        try {
                $requestBody = $request->getParsedBody();
                $chapter_name = $requestBody['chapter_name'];
                $slug = $requestBody['slug'];
                $description = $requestBody['description'];
                $course_id = $requestBody['course_id'];
                $sql = "INSERT INTO chapters (chapter_name, slug, description, course_id, status) VALUES (:chapter_name, :slug, :description, :course_id, 1)";
            
                $db = new Db();
                $conn = $db->connect();
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':chapter_name', $chapter_name);
                $stmt->bindParam(':slug', $slug);
                $stmt->bindParam(':description', $description);
                $stmt->bindParam(':course_id', $course_id);
                $result = $stmt->execute();
                $db = null;
                
                $response->getBody()->write(json_encode($result));
                return $response
                    ->withHeader('content-type', 'application/json')
                    ->withStatus(200);
                } catch (PDOException $e) {
                $error = array(
                    "message" => $e->getMessage()
                );

                $response->getBody()->write(json_encode($error));
                return $response
                    ->withHeader('content-type', 'application/json')
                    ->withStatus(500);
                }
           }
    );

    $app->put('/chapters/{chapterID}', function (Request $request, Response $response, array $args) {
        try {
                $chapterID = $args['chapterID'];
                $requestBody = $request->getParsedBody();
                $chapter_name = $requestBody['chapter_name'];
                $slug = $requestBody['slug'];
                $description = $requestBody['description'];
                $course_id = $requestBody['course_id'];
                $status = $requestBody['status'];
                $sql = "UPDATE chapters SET chapter_name = :chapter_name, slug= :slug, description= :description, course_id= :course_id, status= :status WHERE chapter_id = $chapterID";
            
                $db = new Db();
                $conn = $db->connect();
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':chapter_name', $chapter_name);
                $stmt->bindParam(':slug', $slug);
                $stmt->bindParam(':description', $description);
                $stmt->bindParam(':course_id', $course_id);
                $stmt->bindParam(':status', $status);
                $result = $stmt->execute();
                $db = null;
                
                $response->getBody()->write(json_encode($result));
                return $response
                    ->withHeader('content-type', 'application/json')
                    ->withStatus(200);
                } catch (PDOException $e) {
                $error = array(
                    "message" => $e->getMessage()
                );

                $response->getBody()->write(json_encode($error));
                return $response
                    ->withHeader('content-type', 'application/json')
                    ->withStatus(500);
                }
           }
    );

    $app->get('/chapters/{chapterID}', function (Request $request, Response $response, array $args) {
        try {
                $chapterID = $args['chapterID'];
                $sql = "SELECT * FROM chapters WHERE chapter_id = $chapterID";
            
                $db = new Db();
                $conn = $db->connect();
                $stmt = $conn->prepare($sql);
                $stmt->execute();
                $chapter = $stmt->fetch();
                $db = null;
                
                $response->getBody()->write(json_encode($chapter));
                return $response
                    ->withHeader('content-type', 'application/json')
                    ->withStatus(200);
                } catch (PDOException $e) {
                $error = array(
                    "message" => $e->getMessage()
                );

                $response->getBody()->write(json_encode($error));
                return $response
                    ->withHeader('content-type', 'application/json')
                    ->withStatus(500);
                }
           }
    );

    $app->delete('/chapters/{chapterID}', function (Request $request, Response $response, array $args) {
        try {
                $chapterID = $args['chapterID'];
                $sql = "DELETE FROM chapters WHERE chapter_id = $chapterID";
            
                $db = new Db();
                $conn = $db->connect();
                $stmt = $conn->prepare($sql);
                $result = $stmt->execute();
                $db = null;
                
                $response->getBody()->write(json_encode($result));
                return $response
                    ->withHeader('content-type', 'application/json')
                    ->withStatus(200);
                } catch (PDOException $e) {
                $error = array(
                    "message" => $e->getMessage()
                );

                $response->getBody()->write(json_encode($error));
                return $response
                    ->withHeader('content-type', 'application/json')
                    ->withStatus(500);
                }
           }
    );

};
