FROM php:7.2-cli
COPY . /usr/src/myapp
WORKDIR /usr/src/myapp

# Add user and group with IDs, matching current host user (developer)
ARG hostUID=1000
ARG hostGID=1000
ENV hostUID=$hostUID
ENV hostGID=$hostGID
RUN echo "uid:gid=$hostUID:$hostGID" &&\
    addgroup --gid ${hostGID} app &&\
    adduser --system --uid ${hostUID} --gid $hostGID --shell /sbin/nologin app &&\
    chown -R $hostUID:$hostGID /usr/src/myapp