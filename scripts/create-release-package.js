const fs = require('fs-extra');
const archiver = require('archiver');
const path = require('path');

/**
 * Automated WordPress Plugin Package Creator
 * Creates professional release packages automatically
 */
class WordPressPluginPackager {
    constructor() {
        this.packageInfo = require('../package.json');
        this.pluginDir = path.resolve(__dirname, '..');
        this.distDir = path.join(this.pluginDir, 'dist');
        this.releaseDir = path.join(this.pluginDir, 'releases');
    }

    /**
     * Create release package automatically
     */
    async createReleasePackage() {
        console.log('🚀 Starting automated package creation...');
        
        try {
            // Clean and prepare directories
            await this.prepareDireccories();
            
            // Create WordPress.org package
            await this.createWordPressOrgPackage();
            
            // Create GitHub release package
            await this.createGitHubReleasePackage();
            
            // Create development package
            await this.createDevelopmentPackage();
            
            console.log('✅ All packages created successfully!');
            console.log(`📦 Packages available in: ${this.releaseDir}`);
            
        } catch (error) {
            console.error('❌ Package creation failed:', error);
            process.exit(1);
        }
    }

    /**
     * Prepare directories for package creation
     */
    async prepareDireccories() {
        console.log('📁 Preparing directories...');
        
        // Ensure release directory exists
        await fs.ensureDir(this.releaseDir);
        await fs.ensureDir(this.distDir);
        
        console.log('✅ Directories prepared');
    }

    /**
     * Create WordPress.org compatible package
     */
    async createWordPressOrgPackage() {
        console.log('📦 Creating WordPress.org package...');
        
        const packageName = `wordpress-image-optimization-plugin-v${this.packageInfo.version}-wordpress-org.zip`;
        const packagePath = path.join(this.releaseDir, packageName);
        
        const output = fs.createWriteStream(packagePath);
        const archive = archiver('zip', { zlib: { level: 9 } });
        
        output.on('close', () => {
            console.log(`✅ WordPress.org package created: ${packageName}`);
            console.log(`📊 Package size: ${(archive.pointer() / 1024 / 1024).toFixed(2)} MB`);
        });
        
        archive.on('error', (err) => { throw err; });
        archive.pipe(output);
        
        // Add plugin files for WordPress.org
        const pluginFiles = [
            'admin/',
            'includes/',
            'languages/',
            'image-optimization.php',
            'readme.txt',
            'uninstall.php'
        ];
        
        for (const file of pluginFiles) {
            const filePath = path.join(this.pluginDir, file);
            const stat = await fs.stat(filePath);
            
            if (stat.isDirectory()) {
                archive.directory(filePath, `image-optimization/${file}`);
            } else {
                archive.file(filePath, { name: `image-optimization/${file}` });
            }
        }
        
        await archive.finalize();
    }

    /**
     * Create GitHub release package
     */
    async createGitHubReleasePackage() {
        console.log('📦 Creating GitHub release package...');
        
        const packageName = `wordpress-image-optimization-plugin-v${this.packageInfo.version}-github-release.zip`;
        const packagePath = path.join(this.releaseDir, packageName);
        
        const output = fs.createWriteStream(packagePath);
        const archive = archiver('zip', { zlib: { level: 9 } });
        
        output.on('close', () => {
            console.log(`✅ GitHub release package created: ${packageName}`);
            console.log(`📊 Package size: ${(archive.pointer() / 1024 / 1024).toFixed(2)} MB`);
        });
        
        archive.on('error', (err) => { throw err; });
        archive.pipe(output);
        
        // Add all files including documentation
        const allFiles = [
            'admin/',
            'includes/',
            'languages/',
            'image-optimization.php',
            'readme.txt',
            'uninstall.php',
            'README.md',
            'CHANGELOG.md',
            'LICENSE',
            'CONTRIBUTING.md',
            'package.json'
        ];
        
        for (const file of allFiles) {
            const filePath = path.join(this.pluginDir, file);
            
            if (await fs.pathExists(filePath)) {
                const stat = await fs.stat(filePath);
                
                if (stat.isDirectory()) {
                    archive.directory(filePath, `wordpress-image-optimization-plugin/${file}`);
                } else {
                    archive.file(filePath, { name: `wordpress-image-optimization-plugin/${file}` });
                }
            }
        }
        
        await archive.finalize();
    }

    /**
     * Create development package with source files
     */
    async createDevelopmentPackage() {
        console.log('📦 Creating development package...');
        
        const packageName = `wordpress-image-optimization-plugin-v${this.packageInfo.version}-development.zip`;
        const packagePath = path.join(this.releaseDir, packageName);
        
        const output = fs.createWriteStream(packagePath);
        const archive = archiver('zip', { zlib: { level: 9 } });
        
        output.on('close', () => {
            console.log(`✅ Development package created: ${packageName}`);
            console.log(`📊 Package size: ${(archive.pointer() / 1024 / 1024).toFixed(2)} MB`);
        });
        
        archive.on('error', (err) => { throw err; });
        archive.pipe(output);
        
        // Add everything except node_modules and git
        archive.glob('**/*', {
            cwd: this.pluginDir,
            ignore: [
                'node_modules/**',
                '.git/**',
                'dist/**',
                'releases/**',
                '*.zip',
                '.DS_Store',
                'Thumbs.db'
            ]
        });
        
        await archive.finalize();
    }

    /**
     * Generate package information
     */
    async generatePackageInfo() {
        console.log('📋 Generating package information...');
        
        const packageInfo = {
            name: this.packageInfo.name,
            version: this.packageInfo.version,
            description: this.packageInfo.description,
            author: this.packageInfo.author,
            license: this.packageInfo.license,
            repository: this.packageInfo.repository.url,
            homepage: this.packageInfo.homepage,
            created: new Date().toISOString(),
            packages: [
                {
                    name: `wordpress-image-optimization-plugin-v${this.packageInfo.version}-wordpress-org.zip`,
                    type: 'WordPress.org Submission',
                    description: 'Clean package for WordPress.org plugin directory submission'
                },
                {
                    name: `wordpress-image-optimization-plugin-v${this.packageInfo.version}-github-release.zip`,
                    type: 'GitHub Release',
                    description: 'Complete package for GitHub releases with documentation'
                },
                {
                    name: `wordpress-image-optimization-plugin-v${this.packageInfo.version}-development.zip`,
                    type: 'Development Package',
                    description: 'Full source code package for developers'
                }
            ],
            features: [
                'Manual Image Optimization',
                'WebP/AVIF Conversion',
                'Vietnamese Language Priority',
                'Real-time Progress Tracking',
                'LiteSpeed Cache Integration',
                'No Server Overload',
                'Original Image Preservation'
            ],
            system_requirements: {
                wordpress: '5.0+',
                php: '7.4+',
                extensions: ['Imagick or GD'],
                memory: '128MB+ recommended'
            }
        };
        
        const infoPath = path.join(this.releaseDir, `package-info-v${this.packageInfo.version}.json`);
        await fs.writeJson(infoPath, packageInfo, { spaces: 2 });
        
        console.log(`✅ Package information saved: package-info-v${this.packageInfo.version}.json`);
    }
}

// Execute package creation
const packager = new WordPressPluginPackager();
packager.createReleasePackage()
    .then(() => packager.generatePackageInfo())
    .then(() => {
        console.log('🎉 Automated package creation completed successfully!');
        console.log('📦 Ready for distribution and publishing!');
    })
    .catch(console.error);