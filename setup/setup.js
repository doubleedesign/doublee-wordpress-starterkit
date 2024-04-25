const fs = require('fs').promises;
const path = require('path');
const readline = require('readline');

const rl = readline.createInterface({
	input: process.stdin,
	output: process.stdout
});

// Helper function to wrap readline.question into a promise
function ask(query) {
	return new Promise((resolve) => {
		rl.question(query, resolve);
	});
}

const plugin = './app/public/wp-content/plugins/doublee-client-plugin';
const dryRun = process.argv.includes('--dry-run');

async function processReplacements() {
	const fullName = await ask('Full name of client/project: ');
	await findAndReplace(plugin, 'Client Name', fullName, dryRun);

	const kebabCaseName = await ask('kebab-case name: ');
	await findAndReplace(plugin, 'client-name', kebabCaseName, dryRun);

	const pascalCaseName = await ask('PascalCase name: ');
	await findAndReplace(plugin, 'ClientName', pascalCaseName, dryRun);

	const lowercaseName = await ask('lowercase name: ');
	await findAndReplace(plugin, 'clientname', lowercaseName, dryRun);

	const uppercaseName = await ask('UPPERCASE name: ');
	await findAndReplace(plugin, 'CLIENTNAME', uppercaseName, dryRun);

	if(!dryRun) {
		await renameFile(`${plugin}/clientname.php`, `${plugin}/doublee-${lowercaseName}-plugin.php`);
		await renameFile(`${plugin}/class-clientname.php`, `${plugin}/class-${lowercaseName}.php`);
		await renameDirectory(`${plugin}`, plugin.replace('client', lowercaseName));
	}
	rl.close();
}

async function findAndReplace(startPath, findText, replaceText, dryRun = false) {
	const exclusions = ['README.md'];
	if (exclusions.includes(startPath) || startPath.includes('node_modules')) {
		return;
	}

	const entries = await fs.readdir(startPath, { withFileTypes: true });

	for (const entry of entries) {
		const fullPath = path.join(startPath, entry.name);

		if (entry.isDirectory()) {
			// Recurse into subdirectories
			await findAndReplace(fullPath, findText, replaceText, dryRun);
		}
		else if (entry.isFile() && !exclusions.includes(entry.name)) {
			const data = await fs.readFile(fullPath, 'utf8');
			if (data.includes(findText)) {
				const updatedData = data.replace(new RegExp(findText, 'g'), replaceText);
				if (!dryRun) {
					await fs.writeFile(fullPath, updatedData, 'utf8');
					console.log(`Updated file: ${fullPath}`);
				} else {
					console.log(`Dry run: ${fullPath} would be updated with ${findText} to ${replaceText}`);
				}
			}
		}
	}
}

async function renameFile(oldPath, newPath) {
	try {
		await fs.rename(oldPath, newPath);
		console.log(`File has been renamed from ${oldPath} to ${newPath}`);
	} catch (error) {
		console.error('Error renaming file:', error);
	}
}

async function renameDirectory(oldPath, newPath) {
	try {
		await fs.rename(oldPath, newPath);
		console.log(`Directory renamed from ${oldPath} to ${newPath}`);
	} catch (error) {
		console.error(`Error renaming directory: ${error}`);
	}
}


processReplacements().then();
