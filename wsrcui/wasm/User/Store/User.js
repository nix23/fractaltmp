import React, { Component } from 'react';

class UserStore {
    async setUser(user) {
        try {
            await Promise.resolve();
            return user;
        }
        catch(error) {
            ;
        }
    }

    async getUser() {
        try {
            await Promise.resolve();
            return {
                id: 'fakeUser',
            };
        }
        catch(error) {
            ;
        }
    }

    async rmUser() {
        try {
            await Promise.resolve();
        }
        catch(error) {
            ;
        }
    }
}

module.exports = new UserStore();