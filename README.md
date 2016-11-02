# Red Kohana Module

Red is an ORM based authantication module for Kohana.

Red is part of a set of Kohana modules:
* [Red](https://github.com/davidstutz/kohana-red): ORM based authentication.
* [Green](https://github.com/davidstutz/kohana-green): Red based access control for models and controllers.
* [Blue](https://github.com/davidstutz/kohana-blue): Red based user configuration module.
* [Yellow](https://github.com/davidstutz/kohana-yellow): Green based logging solution.

An introduction can be found [here](http://davidstutz.de/introduction-to-kohana-authentication-using-red/).

**Note to use the correct SQL schema which can be found in`guide/red/sql-schema.md` or `schema.sql`!**

For full documentation see the `guide/` subfolder or use Kohana's [Userguide](https://github.com/kohana/userguide) module.

A demonstration application can be found at [davidstutz/kohana-demo](https://github.com/davidstutz/kohana-demo).

## Composer

Installation via composer:

    {
        "repositories": [
            {
                "type": "vcs",
                "url": "https://github.com/davidstutz/kohana-red"
            }
        ],
        "require": {
            "davidstutz/kohana-red": "dev-master"
        }
    }

## License

Copyright (c) 2013 - 2016 David Stutz
All rights reserved.

Redistribution and use in source and binary forms, with or without modification, are permitted provided that the following conditions are met:

* Redistributions of source code must retain the above copyright notice, this list of conditions and the following disclaimer.
* Redistributions in binary form must reproduce the above copyright notice, this list of conditions and the following disclaimer in the documentation and/or other materials provided with the distribution.
* Neither the name of the copyright holder nor the names of its contributors may be used to endorse or promote products derived from this software without specific prior written permission.

THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
