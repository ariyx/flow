FROM golang:1.26.7-alpine

WORKDIR /app

COPY go.mod ./
RUN go mod download

COPY . .
RUN go build -o /usr/local/bin/webilo-runtime .

CMD ["/usr/local/bin/webilo-runtime"]
