#!/usr/bin/env bash

docker exec -ti competition-redis-1 /bin/sh -c redis-cli FLUSHALL

