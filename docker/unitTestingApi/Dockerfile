FROM alpine:3.15

WORKDIR /app

COPY bin/ ./bin/
COPY docker/unitTestingApi/entrypoint.sh /app/entrypoint.sh

RUN chmod +x /app/bin/VendorApi_linux_x64 /app/entrypoint.sh

ENTRYPOINT ["/app/entrypoint.sh"]
