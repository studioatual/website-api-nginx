# Website API with JWT Authentication

Base Website with Slim Framework and Api JWT Authentication on Docker with Php 7.3.7 Postgres nginx and XDebug

---

## Installation

#### 1. Create a Self-Signed SSL Certificate

Install Certutil

- <b>sudo apt install libnss3-tools -y</b>

Install mkcert

- <b>wget https://github.com/FiloSottile/mkcert/releases/download/v1.1.2/mkcert-v1.1.2-linux-amd64</b>
- <b>mv mkcert-v1.1.2-linux-amd64 mkcert</b>
- <b>chmod +x mkcert</b>
- <b>sudo cp mkcert /usr/local/bin/</b>

Generate Local CA

- <b>mkcert -install</b>

Generate Local SSL Certificates

- <b>sudo mkcert example.com '\*.example.com' localhost 127.0.0.1 ::1</b>

Copy the certificate <b>example.com+4.pem</b> and key <b>example.com+4-key.pem</b> into folder <b>.docker/nginx</b> of your project.

Rename these files to <b>server.pem</b> and <b>server-key.pem</b> and give the permission 644.

- <b>sudo chmod 644 server.pem</b>
- <b>sudo chmod 644 server-key.pem</b>

References

- https://github.com/FiloSottile/mkcert

---

#### 2. Install Website in Terminal

Before the command below make sure if you have a permission 755 on the storage folder

- <b>docker-compose up -d</b>
- <b>docker exec -it php-srv composer install</b>

Before you RUN phinx migrate to populate database
Copy the <b>.env-example</b> => <b>.env</b> and configure fields
Log in postgres-server and create <b>website</b> and <b>tests</b> databases.

and next RUN the commands below

- <b>docker exec -it php-srv vendor/bin/phinx migrate</b>
- <b>docker exec -it php-srv vendor/bin/phinx seed:run</b>

---

#### Run PHPUNIT

- <b>docker exec -it php-srv vendor/bin/phpunit</b>
