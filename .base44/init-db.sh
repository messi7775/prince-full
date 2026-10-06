# Sourced by MariaDB's entrypoint on the first initialization only.
# Keep the hosting schema unchanged; select the local Compose database instead.
sed '/^USE /d' /base44-schema.sql | docker_process_sql --database="$MARIADB_DATABASE"
